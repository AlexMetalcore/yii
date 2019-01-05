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
use backend\helper\HelperGetTrashPhotoFolder;

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
                'only' => ['index'],
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


    public function actionIndex()
    {
        $object_photo = new HelperGetTrashPhotoFolder();
        $what_image = $object_photo->array_image;
        $img_delete = [];
        foreach ($what_image as $img) {
            $img_delete[] = $object_photo->path.$img;
        }
        $trash = count($what_image);
        return $this->render('index' , compact('trash' , 'img_delete'));
    }

    /**
     * @var string
     */
    public function actionClearOldImgs()
    {
        if(!\Yii::$app->request->isAjax) {
            $this->redirect(['site/error']);
        }

        $get_trash_img = new HelperGetTrashPhotoFolder();
        $get_trash_img->deleteTrashImg();

        return $this->renderAjax('ajaxcontent/delete-old-img');
    }

}