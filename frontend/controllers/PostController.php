<?php

namespace frontend\controllers;

use backend\models\LikePosts;
use backend\models\User;
use yii\web\Controller;
use backend\models\Post;
use Yii;
use frontend\components\CategoryWidget;

/**
 * Class PostController
 * @package frontend\controllers
 */
class PostController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    /**
     * @param $id
     * @return string
     */
    public function actionView ($id) {
        $post = Post::find()->where(['id' => $id])->andWhere(['publish_status' => 'publish'])->one();
        if(!$post) {
            $this->redirect(['/site/error']);
        }
        else {
            if (!Yii::$app->request->isAjax) {
                $post->ViwedCounter($id);
            }
            $count = count($post->like);
            if (!Yii::$app->user->isGuest) {
                $user = User::findIdentity(\Yii::$app->user->identity->getId())->username;
                if($user){
                    foreach ($post->like as $like) {
                        if($like->like_author == $user) {
                            $model_author = $user;
                            break;
                        }
                    }
                    return $this->render('view' , compact('post' , 'count' , 'model_author'));
                }
            }
            return $this->render('view' , compact('post' ,'count'));
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionLike($id) {
        if (!Yii::$app->request->isAjax) {
            return false;
        }
        $author_id = \Yii::$app->user->identity->getId();
        $user = User::findIdentity(\Yii::$app->user->identity->getId())->username;
        $model = LikePosts::find()->where(['post_id' => $id])->andWhere(['author_id' => $author_id])->one();
        if ($model && $model->post_id == $id && $model->author_id == $author_id) {
            $model->delete();
        }
        else {
            $model = new LikePosts();
            $model->post_id = $id;
            $model->author_id = $author_id;
            $model->like_author = $user;
            $model->like_post = $model->post->title;
            $model->like_status = 1;
            $model->save();
        }
    }
}