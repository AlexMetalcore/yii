<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.11.18
 * Time: 12:44
 */

namespace backend\models;

use yii\db\ActiveRecord;


/**
 * Class LikePosts
 * @package common\models
 */
class LikePosts extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName ()
    {
        return 'like_posts';
    }

    /**
     * @return array
     */
    public function rules ()
    {
        return [
            [['like_post' , 'like_author'] , 'required'],
            ['like_status' , 'integer']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost ()
    {
        return $this->hasOne(Post::class , ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Post::class, ['id' => 'author_id']);
    }
}