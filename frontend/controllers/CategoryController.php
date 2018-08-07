<?php

namespace frontend\controllers;

use yii\web\Controller;
use backend\models\Post;
use backend\models\Category;
use yii\data\Pagination;

class CategoryController extends Controller {

    public function actionView($id)
    {
        $query = Post::find()->where(['category_id' => ''.$id.'']);
        $category = Category::findOne($id);

        $pages = new Pagination(['totalCount' => $query->count() , 'defaultPageSize' => 4]);

        $posts = $query->offset($pages->offset)->where(['publish_status' => 'publish'])
            ->andWhere(['category_id' => ''.$id.''])->limit($pages->limit)->all();

        return $this->render('view', compact('posts' , 'category' , 'pages'));

    }

}