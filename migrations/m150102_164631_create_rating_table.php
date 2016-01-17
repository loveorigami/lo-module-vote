<?php
use console\db\Migration;

class m150102_164631_create_rating_table extends Migration
{
    protected $tableName = '{{%rating}}';

    public function up()
    {
		
		$this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'status' => 'tinyint(1) NOT NULL DEFAULT 0',
            'author_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
			
			'model_id' => $this->smallInteger()->notNull(),
            'target_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
			'user_ip' => $this->string(39)->notNull(),
            'value' => $this->boolean()->notNull(),
        ]);
		
		$this->createIndex('rating_model_id_target_id', $this->tableName, ['model_id', 'target_id'], false);
        $this->createIndex('rating_user_id', $this->tableName, 'user_id', false);
        $this->createIndex('rating_user_ip', $this->tableName, 'user_ip', false);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
