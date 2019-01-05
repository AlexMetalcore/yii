<?php

use yii\db\Migration;

/**
 * Class m190105_174845_add_new_column_thumb_for_preview_img_in_post_table
 */
class m190105_174845_add_new_column_thumb_for_preview_img_in_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('post', 'thumb_img', 'VARCHAR(255) AFTER `img` ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('post', 'thumb_img');
    }
}
