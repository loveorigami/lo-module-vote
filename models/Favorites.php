<?php

namespace lo\modules\vote\models;

use Yii;
use yii\base\InvalidParamException;

/**
 * This is the model class for table "{{%rating}}".
 *
 * @property integer $id
 * @property integer $model_id
 * @property integer $target_id
 * @property integer $user_id
 * @property string $user_ip
 * @property integer $value
 */
class Favorites extends  \lo\core\db\ActiveRecord
{
	
	use \lo\core\rbac\ConstraintTrait;

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
	
	const VOTE_LIKE = 1;
    const VOTE_DISLIKE = 0;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rating__favs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'target_id', 'user_ip'], 'required'],
            [['model_id', 'target_id', 'user_id'], 'integer'],
            [['user_ip'], 'string', 'max' => 39]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => 'Model ID',
            'target_id' => 'Target ID',
            'user_id' => 'User ID',
            'user_ip' => 'User IP',
        ];
    }

    /**
     * @inheritdoc
     */
    public function metaClass()
    {
        return FavoritesMeta::className();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->delete('favs' . $this->attributes['model_id'] .
            'target' . $this->attributes['target_id']);
        static::updateFavs($this->attributes['model_id'], $this->attributes['target_id']);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param string $modelId Id of model
     * @param integer $targetId Current value of primary key
     */
    public static function updateFavs($modelId, $targetId)
    {
        $cacheKey = 'favs' . $modelId . 'target' . $targetId;
        if (Yii::$app->cache->get($cacheKey) === false) {
            $favs = static::find()->where(['model_id' => $modelId, 'target_id' => $targetId])->count();

            $aggregateModel = AggregateRating::findOne([
                'model_id' => $modelId,
                'target_id' => $targetId,
            ]);
            if (null === $aggregateModel) {
                $aggregateModel = new AggregateRating;
                $aggregateModel->model_id = $modelId;
                $aggregateModel->target_id = $targetId;
            }
            $aggregateModel->favs = $favs;
            $aggregateModel->save();
            Yii::$app->cache->set($cacheKey, true);
        }
    }

}
