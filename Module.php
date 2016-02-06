<?php
/**
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lo\modules\vote;

use yii\base\InvalidConfigException;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'lo\modules\vote\controllers';

    /**
     * Is allow vote for guests
     * @var bool
     */
    public $allowGuests = true;

    /**
     * Is allow change votes
     * @var bool
     */
    public $allowChangeVote = true;

    /**
     * Matching models with ids
     * @var array
     */
    public $models;

    public function init()
    {
        parent::init();
        if (!isset($this->models)) {
            throw new InvalidConfigException('matchingModels not configurated');
        }
        if (empty(Yii::$app->i18n->translations['vote'])) {
            Yii::$app->i18n->translations['vote'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}
