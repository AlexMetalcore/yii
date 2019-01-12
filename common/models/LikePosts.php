<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.11.18
 * Time: 12:44
 */

namespace common\models;


use yii\db\ActiveRecord;
use backend\models\Post;

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
        return $this->hasOne(POst::class, ['id' => 'author_id']);
    }
}