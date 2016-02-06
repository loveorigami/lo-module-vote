<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

use console\db\Migration;
use yii\db\Schema;

class m160126_140022_create_aggregate_rating_table extends Migration
{
    protected $tableName = '{{%rating__aggregate}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => Schema::TYPE_PK,
            'model_id' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'target_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'likes' => Schema::TYPE_INTEGER . ' NOT NULL',
            'dislikes' => Schema::TYPE_INTEGER . ' NOT NULL',
            'favs' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rating' => Schema::TYPE_FLOAT . '(3,2) unsigned NOT NULL'
        ]);
		
		$this->createIndex('rating_agg_model_id_target_id', $this->tableName, ['model_id', 'target_id'], false);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
