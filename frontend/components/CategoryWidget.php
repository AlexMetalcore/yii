<?php
namespace frontend\components;

use backend\models\Category;
use backend\models\Post;
use yii\base\Widget;

/**
 * Class CategoryWidget
 * @package frontend\components
 */
class CategoryWidget extends Widget
{
    /**
     *
     */
    const TIME_CACHE = 3600;

    /**
     * @var string
     */
    protected $limit_popular = 6;

    /**
     *
     */
    public function init()
    {
        parent::init();
    }


    /**
     * @return string
     */
    public function run() {
        $count_posts = [];
        $where = ['publish_status' => 'publish'];
        $categories = Category::find()->all();
        foreach ($categories as $category) {
            $posts = Post::find(['id' => $category->id])->where($where)->all();
            foreach ($posts as $post) {
                if($category->id == $post->category_id) {
                    $count_posts[$category->title][] = [
                        'post_id' => $post->id,
                        'post_name' => $post->title,
                    ];
                }
            }
        }
        $popular = Post::find()
            ->where($where)
            ->orderBy('viewed DESC')
            ->limit($this->limit_popular)
            ->all();

        return $this->render('block-category' , compact('categories' ,'count_posts' , 'popular'));
    }
}