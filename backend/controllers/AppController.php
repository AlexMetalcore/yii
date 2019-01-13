<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.01.19
 * Time: 0:06
 */

namespace backend\controllers;


use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class AppController
 * @package backend\controllers
 */
class AppController extends Controller
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors() ,[
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                        'actions' => ['index' , 'create' , 'view' ,'update' , 'delete'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['moderator'],
                        'actions' => ['index' , 'create' , 'view' ,'update'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST' , 'GET' , 'DELETE' , 'PUT'],
                ],
            ],
        ]);
    }
}