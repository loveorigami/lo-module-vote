<?php

namespace lo\modules\vote\controllers;

use lo\modules\vote\actions\VoteAction;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    public $defaultAction = 'vote';

    public function actions()
    {
        return [
            'vote' => [
                'class' => VoteAction::className(),
            ]
        ];
    }
}
