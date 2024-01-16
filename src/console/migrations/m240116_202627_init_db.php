<?php

use yii\db\Migration;

/**
 * Class m240116_202627_init_db
 */
final class m240116_202627_init_db extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('users', [
            'id INTEGER PRIMARY KEY AUTOINCREMENT',
            'email' => $this->text()->notNull()->unique(),
            'name' => $this->text()->notNull()
        ]);

        $this->createTable('vaults', [
            'id INTEGER PRIMARY KEY AUTOINCREMENT',
            'name' => $this->text()->notNull(),
            'sym_secret' => $this->text()->notNull(),
            'data' => $this->text()->notNull(),
            'owner_id' => $this->integer()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m240116_202627_init_db cannot be reverted.\n";
        return false;
    }
}
