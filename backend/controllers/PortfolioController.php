<?php

namespace backend\controllers;

use Yii;
use backend\models\Portfolio;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helper\HelperImgCompression;

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
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                        'actions' => ['index' , 'create' , 'update' , 'view' , 'delete'],
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
                                /*Сжатие картинок*/
                                new HelperImgCompression(\Yii::$app->basePath.'/web/' , $path);
                                chmod(\Yii::$app->basePath.'/web/'. $path, 0777);
                                $array_img[] = $path;
                                $model->img = implode(',' , $array_img);
                            }
                            catch (\Exception $e) {
                                return $e->getMessage();
                            }
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
        $dataProvider = new ActiveDataProvider([
            'query' => Portfolio::find(),
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
     * @throws \ImagickException
     */
    public function actionCreate()
    {
        $model = new Portfolio();
        $this->handlePortfolioPhoto($model);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
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
            Yii::$app->session->setFlash('delete_portfolio' , 'Работа удалена');
        }
        else {
            return $this->redirect(['index']);
        }
    }
}
