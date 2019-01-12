<?php

namespace backend\controllers;

use Yii;
use backend\models\Settings;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helper\HelperImageFolder;
use backend\helper\HelperImgCompression;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends Controller
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
            'query' => Settings::find(),
            'pagination' => [
                'pageSize' => 4,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
        ]);

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
     */
    public function actionCreate()
    {
        $model = new Settings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
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

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Settings|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'Запрашеваемой страници не существует.'));
    }

    /**
     * @var string
     */
    public function actionClearOldImgs()
    {
        if(!\Yii::$app->request->isAjax) {
            return $this->redirect(['site/error']);
        }

        $get_trash_img = new HelperImageFolder();
        $get_trash_img->deleteTrashImg();

        return $this->renderAjax('ajaxcontent/delete-old-img');
    }

    /**
     * @return string|\yii\web\Response
     * @throws \ImagickException
     */
    public function actionCompressImg() {
        if(!\Yii::$app->request->isAjax) {
            return $this->redirect(['site/error']);
        }
        $object_photo = new HelperImageFolder();
        $staticimg = $object_photo->staticFolderImage();

        if ($staticimg) {
            foreach ($staticimg as $img) {
                new HelperImgCompression(\Yii::$app->basePath.'/web/images/staticimg/' , basename($img));
            }
            \Yii::$app->session->setFlash('compress' , 'Сжатие выполнено');
            return $this->renderAjax('ajaxcontent/compresscache');
        }
    }

    /**
     * @return bool
     */
    public function actionDeleteCache()
    {
        \Yii::$app->cache->flush();
        \Yii::$app->session->setFlash('cache' , 'Кеш очищен');
        return $this->renderAjax('ajaxcontent/compresscache');
    }

    /**
     * @return string
     */
    public function actionCacheDataImg()
    {
        $object_photo = new HelperImageFolder();
        $what_image = $object_photo->array_image;
        $staticimg = $object_photo->staticFolderImage();

        $img_delete = [];
        foreach ($what_image as $img) {
            $img_delete[] = $object_photo->path.$img;
        }
        $trash = count($what_image);

        return $this->render('cache-data-img' , compact('trash' , 'img_delete' , 'staticimg'));
    }
}
