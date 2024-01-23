<?php

use yii\db\Migration;

/**
 * Class m240117_211916_fix_vaults_table
 */
final class m240117_211916_fix_vaults_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        //NOTE: Sqlite não suporta os comandos SQL que nos permitiriam  editar a estrutura sem refazer
        $this->dropTable('users');
        $this->createTable('users', [
            'id INTEGER PRIMARY KEY AUTOINCREMENT',
            'email' => $this->text()->notNull()->unique(),
            'name' => $this->text()->notNull(),
            'active' => $this->boolean()->notNull(),
            'key' => $this->text()->notNull()
        ]);

        $this->dropTable('vaults');
        $this->createTable('vaults', [
            'id INTEGER PRIMARY KEY AUTOINCREMENT',
            'description' => $this->text()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'username' => $this->text(),
            'data' => $this->text(),
            'url' => $this->text(),
            'notes' => $this->text()
        ]);

        $this->createTable('vault_access', [
            'user_id' => $this->integer()->notNull(),
            'vault_id' => $this->integer()->notNull(),
            'secret' => $this->text()->notNull(),
            //NOTE: baseado no diagrama, potencialmente para processo de partilha;
            //poderá ter de vir a ser "time based"
            'nonce' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m240117_211916_fix_vaults_table cannot be reverted.\n";
        return false;
    }
}
