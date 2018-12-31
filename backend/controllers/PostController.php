<?php

namespace backend\controllers;

use backend\models\Category;
use common\models\Portfolio;
use Yii;
use common\models\User;
use backend\models\Post;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\LikePosts;
use backend\helper\HelperImgCompression;

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
     * @throws \ImagickException
     */
    private function handlePostSave(Post $model) {
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->createFilePath() && $model->upload) {
                    $filePath = $model->createFilePath();
                    if ($model->upload->saveAs($filePath)) {
                        new HelperImgCompression($filePath);
                        if($filePath !== $model->img && $model->img) {
                            $fullPath = \Yii::$app->basePath.'/web/'.$model->img.'';
                            if (file_exists($fullPath)){
                                unlink($fullPath);
                            }
                        }
                        $model->img = $filePath;
                    }
                }
            }
        }
    }


    /**
     * @return array
     */
    public function filters()
    {
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

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
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
    public function actionDelete($id)
    {
        $model_post = $this->findModel($id);
        $file_path = \Yii::$app->basePath.'/web/'.$model_post->img.'';
        if(file_exists($file_path) && $model_post->img){
            unlink($file_path);
        }
        $model_post->delete();
        $model = LikePosts::find()->where(['post_id' => $id])->one();
        if($model) {
            $model->delete();
        }
        return $this->redirect(['index']);
    }


    /**
     * @var string
     */
    public function actionClearOldImgs() {
        if(!Yii::$app->request->isAjax) {
            $this->redirect(['site/error']);
        }
        $imgportfolio = [];
        $imgpost = [];
        $allimgmew = [];
        $allimg = scandir(\Yii::$app->basePath.'/web/images');
        $portfolio = Portfolio::find()->all();
        $posts = Post::find()->all();

        foreach ($allimg as $img) {
            if (preg_match('/\.(jpg)|(jpeg)|(bmp)|(png)/', $img)) {
                $allimgmew[] = $img;
            }
        }

        foreach ($portfolio as $img) {
            foreach (explode(',' , $img->img) as $imgitem){
                $imgportfolio[] = basename($imgitem);
            }
        }
        foreach ($posts as $post_img) {
            $imgpost[] = basename($post_img->img);
        }

        $global_array_img = array_merge($imgportfolio , $imgpost);
        $delete_img = array_diff($allimgmew , $global_array_img);

        $what_files = [];
        foreach ($delete_img as $img) {
            $count = count($delete_img);
            $file_delete = \Yii::$app->basePath.'/web/images/'.$img;
            $what_files[] = $file_delete;
            $files_delete = implode('<br>' , $what_files);
            if (file_exists($file_delete)) {
                unlink($file_delete);
            }
            \Yii::$app->session->setFlash('success' , 'Старые фото удалены');
        }
        return $this->renderAjax('ajaxcontent/delete-old-img' , compact('count' , 'files_delete'));
    }
}
