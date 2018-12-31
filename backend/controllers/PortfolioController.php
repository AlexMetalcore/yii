<?php

namespace backend\controllers;

use Yii;
use common\models\Portfolio;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helper\HelperImgUpload;


/**
 * Class PortfolioController
 * @package backend\controllers
 */
class PortfolioController extends Controller
{
    /**
     * @var
     */
    const PARAMETERS_QUALITY = 80;
    /**
     * @var
     */
    const PARAMETERS_COMPRESSION = 8;

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
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Portfolio::find(),
            'pagination' => [
                'pageSize' => 3,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
        ]);
        echo Yii::$app->session->getFlash('success');

        return $this->render('index', [
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
        $files_portfolio = Portfolio::findOne($id)->img;
        if($files_portfolio) {
            $files = explode(',' , $files_portfolio);
            foreach ($files as $file) {
                $path = \Yii::$app->basePath.'/web/'.$file.'';
                if(file_exists($path)){
                    unlink($path);
                }
            }
        }
        $this->findModel($id)->delete();
        if (Yii::$app->request->isAjax) {
            echo 'Запись удалена';
        }
        else {
            return $this->redirect(['index']);
        }
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

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
                    foreach ($files as $file) {
                        $path = 'images/' . uniqid() . '.' . $file->extension;
                        if ($file->saveAs($path)) {
                            try {
                                new HelperImgUpload($path);
                                $array_img[] = $path;
                                $model->img = implode(',' , $array_img);
                            }
                            catch (\Exception $e) {
                                return $e->getCode();
                            }

                        }
                    }
                }
            }
        }
    }
}
