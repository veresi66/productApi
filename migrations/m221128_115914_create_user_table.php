<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m221128_115914_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull(),
            'password' => $this->string(50)->notNull(),
            'authKey' => $this->string(50)->notNull(),
            'accessToken' => $this->string(50)->notNull()
        ]);

        $this->insert('{{%user%}}', [
            'id' => '100',
            'username' => 'admin',
            'password' => '21232f297a57a5a743894a0e4a801fc3',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ]);
        $this->insert('{{%user%}}', [
            'id' => '101',
            'username' => 'demo',
            'password' => 'fe01ce2a7fbac8fafaed7c982a04e229',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
