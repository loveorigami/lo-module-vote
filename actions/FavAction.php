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
                \Yii::$app->session->setFlash('error', Yii::t('vote', 'modelId has not been sent'));
                return false;
            }
            if (null === $targetId = (int)Yii::$app->request->post('targetId')) {
                \Yii::$app->session->setFlash('error', Yii::t('vote', 'The purpose is not defined'));
                return false;
            }
            $act = Yii::$app->request->post('act');
            if (!in_array($act, ['fav-add', 'fav-del'], true)) {
                \Yii::$app->session->setFlash('error', Yii::t('vote', 'Wrong action'));
                return false;
            }

            $userId = Yii::$app->user->getId();
            if ($userId === null && !Rating::getIsAllowGuests($modelId)) {
                \Yii::$app->session->setFlash('error', Yii::t('vote', 'Guests are not allowed to vote'));
                return false;
            }
            if (!$userIp = Rating::compressIp(Yii::$app->request->getUserIP())) {
                \Yii::$app->session->setFlash('error', Yii::t('vote', 'The user is not recognized'));
                return false;
            }
            if (!is_int($modelId)) {
                \Yii::$app->session->setFlash('error', Yii::t('vote', 'The model is not registered'));
                return false;
            }

            if (Rating::getIsAllowGuests($modelId)) {
                $isVoted = Favorites::findOne(['model_id' => $modelId, 'target_id' => $targetId, 'user_ip' => $userIp]);
            } else {
                $isVoted = Favorites::findOne(['model_id' => $modelId, 'target_id' => $targetId, 'user_id' => $userId]);
            }

            if(!is_null($isVoted) && $act == 'fav-del'){
                $isVoted->delete();
                \Yii::$app->session->setFlash('success', Yii::t('vote', 'Item delete from our favorites'));
                return ['success' => true];
            }

            if (is_null($isVoted)) {
                $newVote = new Favorites;
                $newVote->model_id = $modelId;
                $newVote->target_id = $targetId;
                $newVote->user_id = $userId;
                $newVote->user_ip = $userIp;
                if ($newVote->save()) {
                    \Yii::$app->session->setFlash('success', Yii::t('vote', 'Item added to our favorites'));
                    return ['success' => true];
                } else {
                    \Yii::$app->session->setFlash('warning', Yii::t('vote', 'Validation error'));
                    return false;
                }
            } else {
                \Yii::$app->session->setFlash('warning', Yii::t('vote', 'You have already added to favorites!'));
                return false;
            }
        } else {
            throw new MethodNotAllowedHttpException(Yii::t('vote', 'Forbidden method'), 405);
        }
    }

}
