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
     * @param $id
     * @return array|ActiveRecord|null
     */
    public static function getOnePost($id) {
        return self::find()->where(['post_id' => $id])->one();
    }
}