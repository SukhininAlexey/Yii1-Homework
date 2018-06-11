<?php

use yii\db\Migration;

/**
 * Class m180607_212800_add_created_updated_to_users
 */
class m180607_212800_add_created_updated_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'created_at', $this->dateTime());
        $this->addColumn('tasks', 'updated_at', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tasks', 'created_at');
        $this->dropColumn('tasks', 'updated_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180607_212800_add_created_updated_to_users cannot be reverted.\n";

        return false;
    }
    */
}
