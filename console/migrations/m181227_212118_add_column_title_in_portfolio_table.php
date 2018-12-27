<?php

use yii\db\Migration;

/**
 * Class m181227_212118_add_column_title_in_portfolio_table
 */
class m181227_212118_add_column_title_in_portfolio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('portfolio', 'title', 'VARCHAR(255) AFTER `id` ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('portfolio', 'title');
    }
}
