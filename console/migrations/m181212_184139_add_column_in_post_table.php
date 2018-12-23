<?php

use yii\db\Migration;

/**
 * Class m181212_184139_add_column_in_post_table
 */
class m181212_184139_add_column_in_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('post', 'viewed', 'INT(11)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('post', 'viewed');
    }
}
