<?php

use yii\db\Migration;

/**
 * Class m181227_212423_change_type_in_column_content_in_table_portfolio
 */
class m181227_212423_change_type_in_column_content_in_table_portfolio extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE portfolio CHANGE content content TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('ALTER TABLE portfolio CHANGE content content VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL')->execute();
    }

}
