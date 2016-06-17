<?php
namespace lo\modules\vote\models;

use Yii;
use lo\core\db\MetaFields;


/**
 * Class FavoritesMeta
 * Мета описание модели страницы
 * @package app\modules\vote\models\meta
 */
class FavoritesMeta extends MetaFields
{

    /**
     * @inheritdoc
     */
    protected function config()
    {
        return [
            "id_user" => [
                "definition" => [
                    "class" => \lo\core\db\fields\TextField::class,
                    "title" => Yii::t('backend', 'User'),
                    "showInGrid" => false,
                    "showInFilter" => true,
                ],
                "params" => [$this->owner, "id_user"]
            ],
        ];
    }
}