<?php

use yii\db\Migration;

/**
 * Class m190115_140402_add_column_in_table_user_user_img
 */
class m190115_140402_add_column_in_table_user_user_img extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'user_img', 'VARCHAR(255) AFTER `about` ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'user_img');
    }
}
