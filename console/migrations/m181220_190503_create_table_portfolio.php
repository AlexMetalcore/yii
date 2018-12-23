<?php

use yii\db\Migration;

/**
 * Class m181220_190503_create_table_portfolio
 */
class m181220_190503_create_table_portfolio extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('portfolio', [
            'id' => $this->primaryKey(),
            'content' => $this->string(),
            'img' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('portfolio');
    }

}
