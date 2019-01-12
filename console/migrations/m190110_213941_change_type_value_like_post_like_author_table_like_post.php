<?php

use yii\db\Migration;

/**
 * Class m190110_213941_change_type_value_like_post_like_author_table_like_post
 */
class m190110_213941_change_type_value_like_post_like_author_table_like_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE like_posts CHANGE like_post like_post VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL')->execute();
        Yii::$app->db->createCommand('ALTER TABLE like_posts CHANGE like_author like_author VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;')->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('ALTER TABLE like_posts CHANGE like_post like_post VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL')->execute();
        Yii::$app->db->createCommand('ALTER TABLE like_posts CHANGE like_author like_author VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL')->execute();

    }
}
