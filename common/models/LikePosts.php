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

class LikePosts extends ActiveRecord
{
    public static function tableName () {
        return 'like_posts';
    }

    public function rules () {
        return [
            [['like_post' , 'like_author'] , 'required'],
            ['like_status' , 'integer']
        ];
    }

    public function getPost () {
        return $this->hasMany(Post::class , ['post_id' => 'id']);
    }

    public static function getAllLikes ($post_id) {
        $post_count = LikePosts::find()->where(['post_id' => $post_id])->count();
        return $post_count;
    }
    public static function getAllAuthorLikes ($post_id) {
        $author_likes = LikePosts::find()->where(['post_id' => $post_id])->asArray()->all();
        $all_author_like = [];
        foreach ($author_likes as $author){
            $all_author_like[] = $author['like_author'];
        }
        return $all_author_like;
    }
}