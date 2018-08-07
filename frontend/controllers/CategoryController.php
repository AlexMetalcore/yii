<?php

namespace frontend\controllers;

use yii\web\Controller;
use backend\models\Post;
use backend\models\Category;

class CategoryController extends Controller {

    public function actionView($id)
    {
        $posts = Post::find()->where(['category_id' => ''.$id.''])->all();
        $category = Category::findOne($id);

        return $this->render('view', compact('posts' , 'category'));
    }

}