<?php

use yii\db\Migration;

/**
 * Class m240123_180115_adds_tag
 */
final class m240123_180115_adds_tag extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('vault_access', 'tag', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m240123_180115_adds_tag cannot be reverted.\n";
        return false;
    }
}
