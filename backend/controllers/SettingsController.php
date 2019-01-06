<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.19
 * Time: 15:04
 */

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\helper\HelperImageFolder;
use yii\web\ForbiddenHttpException;

/**
 * Class SettingsController
 * @package backend\controllers
 */
class SettingsController extends Controller
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
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => false,
                        'denyCallback' => function ($rule, $action) {
                            throw new ForbiddenHttpException(\Yii::t('app', 'У вас нет доступа к этой странице'));
                        },
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
    public function actionIndex()
    {
        $object_photo = new HelperImageFolder();
        $what_image = $object_photo->array_image;
        $img_delete = [];
        foreach ($what_image as $img) {
            $img_delete[] = $object_photo->path.$img;
        }
        $trash = count($what_image);
        $staticimg = $object_photo->compressFolderImage();

        return $this->render('index' , compact('trash' , 'img_delete' , 'staticimg'));
    }

    /**
     * @var string
     */
    public function actionClearOldImgs()
    {
        if(!\Yii::$app->request->isAjax) {
            $this->redirect(['site/error']);
        }

        $get_trash_img = new HelperImageFolder();
        $get_trash_img->deleteTrashImg();

        return $this->renderAjax('ajaxcontent/delete-old-img');
    }

    /**
     * @param int $compression
     * @param int $quality
     * @return string
     * @throws \ImagickException
     */
    public function actionCompressImg() {
        $object_photo = new HelperImageFolder();
        $staticimg = $object_photo->compressFolderImage();
        if ($staticimg) {
            foreach ($staticimg as $img) {
                $imgs = new \Imagick($img);
                $imgs->setImageCompression(true);
                $imgs->setImageCompression(self::PARAMETERS_COMPRESSION);
                $imgs->setImageCompressionQuality(self::PARAMETERS_QUALITY);
                $imgs->writeImage($img);
                $imgs->clear();
                $imgs->destroy();
            }
            return 'Сжатие выполнено';
        }
    }

    /**
     * @return bool
     */
    public function actionDeleteCache()
    {
        \Yii::$app->cache->flush();
        return 'Кеш очищен';
    }

}