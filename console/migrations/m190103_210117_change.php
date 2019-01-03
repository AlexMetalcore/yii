<?php

use yii\db\Migration;

/**
 * Class m190103_210117_change
 */
class m190103_210117_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        \Yii::$app->db->createCommand('ALTER TABLE post CHANGE publish_date publish_date DATETIME NOT NULL')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \Yii::$app->db->createCommand('ALTER TABLE post CHANGE publish_date publish_date DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')->execute();
    }
}
