<?php
namespace frontend\components;

use backend\models\Category;
use backend\models\Post;
use yii\base\Widget;

/**
 * Class CategoryWidget
 * @package frontend\components
 */
class CategoryWidget extends Widget {

    const STATUS_DRAFT = 'draft';
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
        $categories = Category::find()->all();
        foreach ($categories as $category){
            foreach ($category->posts as $post){
                if($post->publish_status == 'publish') {
                    if($category->id == $post->category_id) {
                        $count_posts[$category->id][] = [
                            $category->id => $post->category_id
                        ];
                    }
                }
            }
        }
        return $this->render('block-category' , compact('categories' ,'count_posts'));
    }

}