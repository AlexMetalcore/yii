<?php
namespace frontend\controllers;

use common\models\Portfolio;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use backend\models\Post;
use yii\data\Pagination;
use common\models\User;
/**
 * Site controller
 */
class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $posts = Post::find()->where(['publish_status' => 'publish'])
            ->orderBy(['id' => SORT_DESC])->limit(6)->all();
        return $this->render('index' , compact('posts'));
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login() ) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Письмо успешно отправлено');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка отправки');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionPortfolio()
    {
        $portfolios = Portfolio::find()->all();
        return $this->render('portfolio' , compact('portfolios'));
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте ваш e-mail');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Вы не можете восстановить данный пароль');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionBlog () {
        $where_publish = ['publish_status' => 'publish'];
        $query = Post::find();
        $pages = new Pagination(['totalCount' => $query->where($where_publish)->count() , 'defaultPageSize' => 3]);

        $posts = $query->offset($pages->offset)->where($where_publish)->limit($pages->limit)->all();

        return $this->render('blog' , compact('posts' ,'pages'));

    }

    public function actionSearch (){
        $where_publish = ['publish_status' => 'publish'];
        if(Yii::$app->request->isAjax){
            $this->layout = false;
        }
        $search_query = Yii::$app->request->get('search_query');
        $query = Post::find()->where(['OR', ['like' , 'title' , $search_query] , ['like' , 'content' , $search_query]])->andWhere(['publish_status' => 'publish']);

        $pages = new Pagination(['totalCount' => $query->where($where_publish)->count() , 'defaultPageSize' => 3]);

        $count = $query->count();

        $posts = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('search' , compact('posts' , 'pages' , 'search_query' , 'count'));

    }
    public function actionPageDraft ($id) {
        $post = Post::findOne($id);
        if($post->publish_status != 'draft') {
            $this->redirect(['/post/view' , 'id' => $id]);
        }
        return $this->render('page-draft' , compact('post'));
    }
}
