<?php

namespace frontend\controllers;

use yii\web\Controller;
use backend\models\Post;

class PostController extends Controller{

    public function actionView ($id) {

        $post = Post::findOne($id);
        return $this->render('view' , compact('post'));

    }
}