<?php

namespace lo\modules\vote\behaviors;

use lo\modules\vote\models\Rating;
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
    public function getLikes()
    {
        return $this->owner
            ->hasOne(AggregateRating::className(), [
                'target_id' => $this->owner->primaryKey()[0],
            ])
            ->select('likes')
            ->where('model_id = :modelId', [
                ':modelId' => Rating::getModelIdByName($this->owner->className())
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getDislikes()
    {
        return $this->owner
            ->hasOne(AggregateRating::className(), [
                'target_id' => $this->owner->primaryKey()[0],
            ])
            ->select('dislikes')
            ->where('model_id = :modelId', [
                ':modelId' => Rating::getModelIdByName($this->owner->className())
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getRating()
    {
        return $this->owner
            ->hasOne(AggregateRating::className(), [
                'target_id' => $this->owner->primaryKey()[0],
            ])->onCondition(['model_id' => Rating::getModelIdByName($this->owner->className())]);
    }

    /**
     * @inheritdoc
     */
    public function getLikesCount()
    {
        return $this->likes->scalar();
    }

    /**
     * @inheritdoc
     */
    public function getDislikesCount()
    {
        return $this->dislikes->scalar();
    }

    /**
     * @inheritdoc
     */
    public function getRatingNumber()
    {
        return $this->rating->scalar();
    }
}
