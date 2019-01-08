<?php

use yii\db\Migration;

/**
 * Class m190108_005326_change_type_time_in_publish_date_column
 */
class m190108_005326_change_type_time_in_publish_date_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE post CHANGE publish_date publish_date DATE NOT NULL')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('ALTER TABLE post CHANGE publish_date publish_date DATETIME NOT NULL')->execute();
    }
}
