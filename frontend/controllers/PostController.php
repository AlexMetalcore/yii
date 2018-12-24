<?php

namespace frontend\controllers;

use common\models\LikePosts;
use common\models\User;
use yii\web\Controller;
use backend\models\Post;
use Yii;
use frontend\components\CategoryWidget;

/**
 * Class PostController
 * @package frontend\controllers
 */
class PostController extends Controller{

    /**
     * @param $id
     * @return string
     */
    public function actionView ($id) {
        $model = Post::findOne($id);
        if(!$model) {
            $this->redirect(['/site/error']);
        }
        else if ($model->publish_status == CategoryWidget::STATUS_DRAFT) {
            $this->redirect(['/site/page-draft' , 'id' => $id]);
        }
        else {
                if(!Yii::$app->request->isAjax){
                    $model->ViwedCounter($id);
                }
                $post = Post::find()->where(['id' => $id])->andWhere(['publish_status' => 'publish'])->one();
                $count = LikePosts::getAllLikes($post->id);
                $author_likes = LikePosts::getAllAuthorLikes($post->id);
                if (!Yii::$app->user->isGuest) {
                    if(User::findIdentity(\Yii::$app->user->identity->getId())->username){
                        $user = User::findIdentity(\Yii::$app->user->identity->getId())->username;
                        $model_author = LikePosts::find()->where(['post_id' => $id])->andWhere(['like_author' => $user])->one();
                        return $this->render('view' , compact('post' , 'count' , 'author_likes' ,'model_author'));
                    }
                }
                return $this->render('view' , compact('post' ,'count' , 'author_likes'));
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
        $post = Post::findOne($id);
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
            $model->like_post = $post->title;
            $model->like_status = 1;
            $model->save();
        }
    }
}