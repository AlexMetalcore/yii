<?php
namespace frontend\components;

use backend\models\Category;
use backend\models\Post;
use yii\base\Widget;
use yii\helpers\Url;
use Yii;

class CategoryWidget extends Widget {

    public function init()
    {
        parent::init();
    }

    public function run() {

        $categories = Category::find()->asArray()->all();
        foreach ($categories as $category){
            $post = Post::find()->where(['category_id' => $category['id']])->count();
            echo "<a href=". Url::to(['category/view' , 'id' => $category['id']]).">".$category["title"]."</a>  "
                .'<span class="count_post">('.$post.')</span>'."<br>";
        }
    }

}