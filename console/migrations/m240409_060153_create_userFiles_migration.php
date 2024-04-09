<?php

use yii\db\Migration;

/**
 * Class m240409_060153_create_userFiles_migration
 */
class m240409_060153_create_userFiles_migration extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%file_user}}', [
            'id' => $this->primaryKey(),
            'file_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%file_user}}');
    }
}
