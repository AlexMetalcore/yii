<?php

namespace frontend\controllers;

use yii\web\Controller;
use backend\models\Post;
use backend\models\Category;
use yii\data\Pagination;

/**
 * Class CategoryController
 * @package frontend\controllers
 */
class CategoryController extends Controller {


    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $category = Category::findOne($id);

        if (!$category) {
            return $this->redirect(['site/error']);
        }
        $query = Post::find()
            ->where(['category_id' => ''.$id.''])
            ->andWhere(['publish_status' => 'publish']);

        $pages = new Pagination(['totalCount' => $query->count() , 'defaultPageSize' => 6]);

        $posts = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        if(!$posts) {
            \Yii::$app->session->setFlash('empty-posts' , 'Статтей в категории пока нету...');
        }

        return $this->render('view', compact('posts' , 'category' , 'pages'));

    }

}