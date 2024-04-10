<?php

use yii\db\Migration;

/**
 * Class m240410_031033_add_access_token_to_user_table
 */
class m240410_031033_add_access_token_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240410_031033_add_access_token_to_user_table cannot be reverted.\n";

        return false;
    }

    public function up()
{
    $this->addColumn('{{%user}}', 'access_token', $this->string()->unique());
}

public function down()
{
    $this->dropColumn('{{%user}}', 'access_token');
}

}
