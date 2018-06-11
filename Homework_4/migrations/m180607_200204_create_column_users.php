<?php

use yii\db\Migration;

/**
 * Class m180607_200204_create_column_users
 */
class m180607_200204_create_column_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'email', $this->string(100)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'email');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180607_200204_create_column_users cannot be reverted.\n";

        return false;
    }
    */
}
