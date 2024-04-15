<?php

use yii\db\Migration;

/**
 * Class m240415_031747_create_accessfile
 */
class m240415_031747_create_accessfile extends Migration
{
   /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file_access}}', [
            'id' => $this->primaryKey(),
            'file_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file_access}}');
    }
}
