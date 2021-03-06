<?php

namespace backend\controllers;

use backend\models\Category;
use Yii;
use backend\models\User;
use backend\models\Post;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use backend\helper\HelperImgCompression;
use backend\models\PostSearch;

/**
 * Class PostController
 * @package backend\controllers
 */
class PostController extends AppController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return parent::behaviors();
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

        throw new NotFoundHttpException('Запрашеваемой страници не существует.');
    }

    /**
     * @param Post $model
     * @throws \ImagickException
     */
    private function imgPostSave(Post $model) {
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->createFilePath() && $model->upload) {
                    $filePath = $model->createFilePath();
                    if ($model->upload->saveAs($filePath)) {
                        new HelperImgCompression(\Yii::$app->basePath.'/web/' , $filePath);
                        chmod(\Yii::$app->basePath.'/web/'. $filePath, 0777);
                        $model->thumb_img = $model->setImgThumb($filePath);
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
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', compact('model'));
    }


    /**
     * @return string|\yii\web\Response
     * @throws \ImagickException
     */
    public function actionCreate()
    {
        $model = new Post();
        $this->imgPostSave($model);
        $category = Category::getAllCategory();
        $authors = User::getAllUser();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create',compact('model' , 'category' ,'authors'));
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \ImagickException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->imgPostSave($model);
        $category = Category::getAllCategory();
        $authors = User::getAllUser();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', compact('model' , 'category' ,'authors'));
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
        $model_post->delete();

        foreach ($model_post->like as $like) {
            if ($like) {
                $like->delete();
            }
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash('delete' , 'Запись удалена');
        }
        else {
            return $this->redirect(['index']);
        }
    }
}
