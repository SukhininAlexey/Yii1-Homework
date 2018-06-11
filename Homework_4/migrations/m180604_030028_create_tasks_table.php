<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m180604_030028_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tasks', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'date' => $this->dateTime()->notNull(),
            'description' => $this->text(),
            'user_id' => $this->integer()
        ]);
        
        $this->addForeignKey('fk_tasks_users', 'tasks', 'user_id', 'users', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tasks');
    }
}
