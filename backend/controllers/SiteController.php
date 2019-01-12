<?php
namespace backend\controllers;

use common\components\rbac\UserRoleRule;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use backend\models\User;
use backend\models\Post;
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
                'only'  => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin' , 'moderator'],
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
        $portfolios = \backend\models\Portfolio::getLastPortfolio();

        return $this->render('index' , compact('posts' , 'users' , 'populars' , 'portfolios'));
    }


    /**
     * @return string|\yii\console\Response|\yii\web\Response
     */
    public function actionLogin()
    {
        $this->layout = 'login-layout.php';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->loginAdmin()) {
            $user_status = User::findIdentity(\Yii::$app->user->identity->getId())->status;
            $success = Yii::$app->session->setFlash('success' , 'Вы авторизировались как '.$model->username.' ');
            if ($user_status === User::ROLE_MODERATOR) {
                $success;
                return $this->redirect(['post/index']);
            }
            $success;
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
