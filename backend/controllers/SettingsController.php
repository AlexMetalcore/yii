<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.19
 * Time: 15:04
 */

namespace backend\controllers;

use backend\helper\HelperImgCompression;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\helper\HelperImageFolder;

/**
 * Class SettingsController
 * @package backend\controllers
 */
class SettingsController extends Controller
{
    /**
     * @return array
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
    public function actionIndex()
    {
        $object_photo = new HelperImageFolder();
        $what_image = $object_photo->array_image;
        $staticimg = $object_photo->staticFolderImage();

        $img_delete = [];
        foreach ($what_image as $img) {
            $img_delete[] = $object_photo->path.$img;
        }
        $trash = count($what_image);

        return $this->render('index' , compact('trash' , 'img_delete' , 'staticimg'));
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
     * @param int $compression
     * @param int $quality
     * @return string
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