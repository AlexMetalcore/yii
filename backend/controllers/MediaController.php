<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.01.19
 * Time: 0:05
 */

namespace backend\controllers;

use backend\helper\HelperImageFolder;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


/**
 * Class MediaController
 * @package backend\controllers
 */
class MediaController extends Controller
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
    public function actionIndex ()
    {
        $images = new HelperImageFolder();
        $imgs = $images->getAllImages();
        return $this->render('index' , compact('imgs'));
    }
}