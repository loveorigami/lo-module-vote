<?php

namespace lo\modules\vote\actions;

use lo\modules\vote\models\Rating;
use yii\base\Action;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use Yii;

class VoteAction extends Action
{
    public function run()
    {
        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (null === $modelId = (int)Yii::$app->request->post('modelId')) {
                \Yii::$app->session->setFlash('error', Yii::t('vote', 'modelId has not been sent'));
                return ['content' => Yii::t('vote', 'modelId has not been sent')];
            }
            if (null === $targetId = (int)Yii::$app->request->post('targetId')) {
                return ['content' => Yii::t('vote', 'The purpose is not defined')];
            }
            $act = Yii::$app->request->post('act');
            if (!in_array($act, ['like', 'dislike'], true)) {
                return ['content' => Yii::t('vote', 'Wrong action')];
            }
            $value = $act === 'like' ? Rating::VOTE_LIKE : Rating::VOTE_DISLIKE;
            $userId = Yii::$app->user->getId();
            if ($userId === null && !Rating::getIsAllowGuests($modelId)) {
                return ['content' => Yii::t('vote', 'Guests are not allowed to vote')];
            }
            if (!$userIp = Rating::compressIp(Yii::$app->request->getUserIP())) {
                return ['content' => Yii::t('vote', 'The user is not recognized')];
            }
            if (!is_int($modelId)) {
                return ['content' => Yii::t('vote', 'The model is not registered')];
            }
            if (Rating::getIsAllowGuests($modelId)) {
                $isVoted = Rating::findOne(['model_id' => $modelId, 'target_id' => $targetId, 'user_ip' => $userIp]);
            } else {
                $isVoted = Rating::findOne(['model_id' => $modelId, 'target_id' => $targetId, 'user_id' => $userId]);
            }
            if (is_null($isVoted)) {
                $newVote = new Rating;
                $newVote->model_id = $modelId;
                $newVote->target_id = $targetId;
                $newVote->user_id = $userId;
                $newVote->user_ip = $userIp;
                $newVote->value = $value;
                if ($newVote->save()) {
                    if ($value === Rating::VOTE_LIKE) {
                        \Yii::$app->session->setFlash('success', Yii::t('vote', 'Your vote is accepted. Thanks!'));
                        return ['content' => Yii::t('vote', 'Your vote is accepted. Thanks!'), 'type'=>'success', 'success' => true];
                    } else {
                        \Yii::$app->session->setFlash('success', Yii::t('vote', 'Thanks for your opinion'));
                        return ['content' => Yii::t('vote', 'Thanks for your opinion'), 'type'=>'success', 'success' => true];
                    }
                } else {
                    \Yii::$app->session->setFlash('error', Yii::t('vote', 'Validation error'));
                    return ['content' => Yii::t('vote', 'Validation error'), 'type'=>'error'];
                }
            } else {
                if ($isVoted->value !== $value && Rating::getIsAllowChangeVote($modelId)) {
                    $isVoted->value = $value;
                    if ($isVoted->save()) {
                        \Yii::$app->session->setFlash('success', Yii::t('vote', 'Your vote has been changed. Thanks!'));
                        return ['content' => Yii::t('vote', 'Your vote has been changed. Thanks!'), 'type'=>'success','success' => true, 'changed' => true];
                    } else {
                        \Yii::$app->session->setFlash('error', Yii::t('vote', 'Validation error'));
                        return ['content' => Yii::t('vote', 'Validation error'), 'type'=>'error'];
                    }
                }
                \Yii::$app->session->setFlash('warning', Yii::t('vote', 'You have already voted!'));
                return ['content' => Yii::t('vote', 'You have already voted!'), 'type'=>'warning'];
            }
        } else {
            throw new MethodNotAllowedHttpException(Yii::t('vote', 'Forbidden method'), 405);
        }
    }

}
