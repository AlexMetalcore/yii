<?php

use yii\db\Migration;

/**
 * Class m181226_001506_add_column_in_portfolio_table
 */
class m181226_001506_add_column_in_portfolio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        \Yii::$app->db->createCommand('ALTER TABLE portfolio CHANGE title title VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ')->execute();
        \Yii::$app->db->createCommand('ALTER TABLE portfolio CHANGE content content TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('portfolio', 'title');
    }
}
