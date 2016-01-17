<?php
namespace lo\modules\vote\models;

use Yii;
use lo\core\db\MetaFields;


/**
 * Class RatingMeta
 * Мета описание модели страницы
 * @package app\modules\banners\models\meta
 */
class RatingMeta extends MetaFields
{

    /**
     * @inheritdoc
     */
    protected function config()
    {
        return [
            "id_user" => [
                "definition" => [
                    "class" => \lo\core\db\fields\TextField::className(),
                    "title" => Yii::t('backend', 'User'),
                    "showInGrid" => false,
                    "showInFilter" => true,
                ],
                "params" => [$this->owner, "id_user"]
            ],
        ];
    }
}