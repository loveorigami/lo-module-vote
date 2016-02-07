<?php

namespace lo\modules\vote\behaviors;

use lo\modules\vote\models\Rating;
use lo\modules\vote\models\Favorites;
use lo\modules\vote\models\AggregateRating;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

class RatingBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        if (!$owner instanceof ActiveRecord) {
            throw new InvalidConfigException(Yii::t('vote', 'Please attach this behavior to the instance of the ActiveRecord class'));
        }
        parent::attach($owner);
    }

    /**
     * @inheritdoc
     */
    public function getAggregate()
    {
        return $this->owner
            ->hasOne(AggregateRating::className(), [
                'target_id' => $this->owner->primaryKey()[0],
            ])
            ->onCondition([
                'model_id' => Rating::getModelIdByName($this->owner->className())
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getVoted()
    {
        return $this->owner
            ->hasOne(Rating::className(), [
                'target_id' => $this->owner->primaryKey()[0],
            ])
            ->from(Rating::tableName() . ' r')
            ->onCondition([
                'r.model_id' => Rating::getModelIdByName($this->owner->className()),
                'r.user_id' => \Yii::$app->user->id,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getFaved()
    {
        return $this->owner
            ->hasOne(Favorites::className(), [
                'target_id' => $this->owner->primaryKey()[0],
            ])
            ->from(Favorites::tableName() . ' f')
            ->onCondition([
                'f.model_id' => Rating::getModelIdByName($this->owner->className()),
                'f.user_id' => \Yii::$app->user->id,
            ]);
    }

}
