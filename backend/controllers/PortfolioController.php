<?php

namespace backend\controllers;

use Yii;
use common\models\Portfolio;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helper\HelperImgCompression;
use backend\helper\HelperGetTrashPhotoFolder;


/**
 * Class PortfolioController
 * @package backend\controllers
 */
class PortfolioController extends Controller
{
    /**
     * {@inheritdoc}
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
                        'roles' => ['admin'],
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
     * @return Portfolio|null
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if (($model = Portfolio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'Запрашиваемой страници не существует.'));
    }

    /**
     * @param Portfolio $model
     * @return int|mixed
     */
    private function handlePortfolioPhoto(Portfolio $model) {
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->createFilePath() && $model->gallery) {
                    $files = $model->createFilePath();
                    $array_img = [];
                    foreach ($files as $file) {
                        $path = 'images/' . uniqid() . '.' . $file->extension;
                        if ($file->saveAs($path)) {
                            try {
                                new HelperImgCompression($path);
                                $array_img[] = $path;
                                $model->img = implode(',' , $array_img);
                            }
                            catch (\Exception $e) {
                                return $e->getMessage();
                            }
                        }
                        else {
                            echo 'Ошибка';

                            die;
                        }
                    }
                }
            }
        }
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $get_img_trash = new HelperGetTrashPhotoFolder();
        $trash = $get_img_trash->count;

        $dataProvider = new ActiveDataProvider([
            'query' => Portfolio::find(),
            'pagination' => [
                'pageSize' => 3,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'trash' => $trash,
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
     * @throws \ImagickException
     */
    public function actionCreate()
    {
        $model = new Portfolio();
        $this->handlePortfolioPhoto($model);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
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
        $this->handlePortfolioPhoto($model);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
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
        $this->findModel($id)->delete();
        if (Yii::$app->request->isAjax) {
            echo 'Запись удалена';
        }
        else {
            return $this->redirect(['index']);
        }
    }
}
