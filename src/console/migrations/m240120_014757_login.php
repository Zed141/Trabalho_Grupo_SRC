<?php

use yii\db\Migration;

/**
 * Class m240120_014757_login
 */
class m240120_014757_login extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('users', 'last_login', $this->dateTime());

        $this->createTable('login_tokens', [
            'id INTEGER PRIMARY KEY AUTOINCREMENT',
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'expired' => $this->boolean()->notNull(),
            'token' => $this->text(),
            'used_at' => $this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m240120_014757_login cannot be reverted.\n";
        return false;
    }
}
