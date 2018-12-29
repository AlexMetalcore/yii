<?php

namespace backend\controllers;

use backend\models\Category;
use Yii;
use common\models\User;
use backend\models\Post;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\LikePosts;


/**
 * Class PostController
 * @package backend\controllers
 */
class PostController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index' , 'create' , 'update' , 'view' , 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET' , 'POST' , 'DELETE'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function filters() {
        return [[
                'COutputCache',
                'duration'=> 60,
                'varyByParam'=>['id'],
            ],];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $post = Post::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $post,
            'pagination' => [
                'pageSize' => 3,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Post();
        $this->handlePostSave($model);
        $category = Category::getAllCategory();
        $authors = User::getAllUser();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'category' => $category,
            'authors' => $authors
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->handlePostSave($model);
        $category = Category::getAllCategory();
        $authors = User::getAllUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'category' => $category,
            'authors' => $authors,
        ]);
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {

        $file_path = \Yii::$app->basePath.'/web/'.$this->findModel($id)->img.'';
        if(file_exists($file_path) && $this->findModel($id)->img){
            unlink($file_path);
        }
        $this->findModel($id)->delete();
        $model = LikePosts::find()->where(['post_id' => $id])->one();
        if($model) {
            $model->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Post|null
     * @throws NotFoundHttpException
     */
    private function findModel($id) {

        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param Post $model
     * @return \yii\web\Response
     */
    private function handlePostSave(Post $model) {
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->createFilePath() && $model->upload) {
                    $filePath = $model->createFilePath();
                    if ($model->upload->saveAs($filePath)) {
                        $model->img = $filePath;
                    }
                }
            }
        }
    }
}
