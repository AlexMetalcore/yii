<?php
namespace backend\controllers;

use backend\models\Post;
use common\models\Portfolio;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use yii\web\ForbiddenHttpException;

/**
 * Class SiteController
 * @package backend\controllers
 */
class SiteController extends Controller
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
                    [
                        'allow' => false,
                        'denyCallback' => function ($rule, $action) {
                            throw new ForbiddenHttpException(Yii::t('app', 'У вас нет доступа к этой странице'));
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $posts = Post::getLastPost();
        $users = User::getLastRegisteredUser();
        $populars = Post::getPopularPosts();
        $portfolios = Portfolio::getLastPortfolio();
        return $this->render('index' , compact('posts' , 'users' , 'populars' , 'portfolios'));
    }


    /**
     * @return string|\yii\console\Response|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->loginAdmin()) {
            Yii::$app->session->setFlash('success' , 'Вы авторизировались как '.$model->username.' ');
            return $this->redirect(['/']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
