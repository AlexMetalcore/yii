<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.01.19
 * Time: 0:05
 */

namespace backend\controllers;

use backend\helper\HelperImageFolder;
use backend\helper\HelperImgCompression;
use backend\models\Media;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;


/**
 * Class MediaController
 * @package backend\controllers
 */
class MediaController extends Controller
{
    /**
     * @param Media $model
     * @throws \ImagickException
     */
    private function imgMediaSave(Media $model) {
        if ($files = $model->createFilePath() && $model->media_upload && $model->attributes['media_upload'] !== null) {
            foreach ($files as $file) {
                $path = 'images/' . uniqid() . '.' . $file->extension;
                if ($file->saveAs($path)) {
                    try {
                        /*Сжатие картинок*/
                        new HelperImgCompression(\Yii::$app->basePath.'/web/' , $path);
                        chmod(\Yii::$app->basePath.'/web/'. $path, 0777);
                    }
                    catch (\Exception $e) {
                        return $e->getMessage();
                    }
                }
            }
        }
        else {
            return $model->addError('media_upload[]' , 'Выберите файл для загрузки');
        }
    }

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
     * @throws \ImagickException
     */
    public function actionIndex ()
    {
        $model = new Media();
        if(\Yii::$app->request->isPost) {
            $this->imgMediaSave($model);
            $this->refresh();
        }
        $images = new HelperImageFolder();
        $imgs = $images->getAllImages();
        return $this->render('index' , compact('model' ,'imgs'));
    }

    public function actionDeleteItemImg ($name_img)
    {
        $path_static = \Yii::$app->basePath . '/web/images/staticimg/';
        $img =  \Yii::$app->basePath . '/web/images/';

        if(!\Yii::$app->request->isAjax) {
            return false;
        }
        if (file_exists($path_static . $name_img)) {
            unlink($path_static . $name_img);
            return 'Картинка удалена';
        }
        else if (file_exists($img . $name_img)) {
            unlink($img . $name_img);
            return 'Картинка удалена';
        }
        return false;
    }
}