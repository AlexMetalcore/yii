<?php

namespace frontend\controllers;

use yii\web\Controller;
use backend\models\Post;
use common\models\User;
use backend\models\Category;

class PostController extends Controller{

    public function actionView ($id) {

        $post = Post::findOne($id);
        return $this->render('view' , compact('post'));

    }
}