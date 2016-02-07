<?php

namespace lo\modules\vote\actions;

use lo\modules\vote\models\Favorites;
use lo\modules\vote\models\Rating;
use yii\base\Action;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use Yii;

class FavAction extends Action
{
    public function run()
    {
        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (null === $modelId = (int)Yii::$app->request->post('modelId')) {
                return ['content' => Yii::t('vote', 'modelId has not been sent')];
            }
            if (null === $targetId = (int)Yii::$app->request->post('targetId')) {
                return ['content' => Yii::t('vote', 'The purpose is not defined')];
            }
            $act = Yii::$app->request->post('act');
            if (!in_array($act, ['fav-add', 'fav-del'], true)) {
                return ['content' => Yii::t('vote', 'Wrong action')];
            }

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
                $isVoted = Favorites::findOne(['model_id' => $modelId, 'target_id' => $targetId, 'user_ip' => $userIp]);
            } else {
                $isVoted = Favorites::findOne(['model_id' => $modelId, 'target_id' => $targetId, 'user_id' => $userId]);
            }


            if(!is_null($isVoted) && $act == 'fav-del'){
                $isVoted->delete();
                return ['content' => Yii::t('vote', 'Item delete from our favorites'), 'type'=>'success', 'success' => true];
            }

            if (is_null($isVoted)) {
                $newVote = new Favorites;
                $newVote->model_id = $modelId;
                $newVote->target_id = $targetId;
                $newVote->user_id = $userId;
                $newVote->user_ip = $userIp;
                if ($newVote->save()) {
                    return ['content' => Yii::t('vote', 'Item added to our favorites'), 'type'=>'success', 'success' => true];
                } else {
                    return ['content' => Yii::t('vote', 'Validation error'), 'type'=>'warning'];
                }
            } else {
                return ['content' => Yii::t('vote', 'You have already added to favorites!'), 'type'=>'warning'];
            }
        } else {
            throw new MethodNotAllowedHttpException(Yii::t('vote', 'Forbidden method'), 405);
        }
    }

}
