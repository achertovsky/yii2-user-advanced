<?php

use yii\db\Migration;

/**
 * Class m211016_173024_user_tweaks
 */
class m211016_173024_user_tweaks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('{{%user}}')->getColumn('username')) {
            $this->alterColumn('{{%user}}', 'username', $this->string());
            $this->dropIndex('username', '{{%user}}');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211016_173024_user_tweaks cannot be reverted.\n";
    }
}
