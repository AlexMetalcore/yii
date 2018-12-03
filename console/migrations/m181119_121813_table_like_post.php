<?php

use yii\db\Migration;

/**
 * Class m181119_121813_table_like_post
 */
class m181119_121813_table_like_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('like_posts', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'author_id' => $this->integer(),
            'like_post' => $this->string(),
            'like_author' => $this->string(),
            'like_status' => $this->integer()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('like_posts');
    }
}
