<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lo\modules\vote\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use lo\modules\vote\behaviors\RatingBehavior;
use lo\modules\vote\models\Rating;
 
class VoteBootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
		$models = Yii::$app->getModule('vote')->models;
		foreach ($models as $value) {
			$modelId = Rating::getModelIdByName($value);
			$modelName = Rating::getModelNameById($modelId);
			Event::on($modelName::class, $modelName::EVENT_INIT, function ($event) {
			    if (null === $event->sender->getBehavior('rating')) {
					$event->sender->attachBehavior('rating', [
						'class' => RatingBehavior::class,
					]);
				}
			});
			Event::on($modelName::class, $modelName::EVENT_AFTER_FIND, function ($event) {
				$modelId = Rating::getModelIdByName($event->sender->className());
				$targetId = $event->sender->{$event->sender->primaryKey()[0]};
	            Rating::updateRating($modelId, $targetId);
	        });
		}
    }
}
