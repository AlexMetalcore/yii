<?php

use yii\db\Migration;

/**
 * Class m190107_220322_add_column_parent_id_in_table_category
 */
class m190107_220322_add_column_parent_id_in_table_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('category', 'parent_id', 'INT(11) AFTER `id` ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('category', 'parent_id');
    }
}
