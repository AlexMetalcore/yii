<?php
namespace frontend\controllers;

use common\repositories\UserRepositoryInterface;
use backend\models\Portfolio;
use backend\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use backend\models\ContactForm;
use backend\models\Post;
use yii\data\Pagination;
use backend\models\Settings;
/**
 * Class SiteController
 * @package frontend\controllers
 */
class SiteController extends Controller
{
    private $userRepository;

    public function __construct($id, $module, UserRepositoryInterface $userRepository, $config = [])
    {
        $this->userRepository = $userRepository;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
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

    /**
     * @return string
     */
    public function actionIndex()
    {
        var_dump($this->userRepository);die;
        $posts = Post::getLastPost();

        return $this->render('index' , compact('posts'));
    }

    public function actionLogin()
    {
        if(!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            return $this->render('login', compact('model'));
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

    /**
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                    Yii::$app->session->setFlash('success', 'Письмо успешно отправлено');
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка отправки');
                }
                return $this->refresh();
            }
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * @return string
     */
    public function actionPortfolio()
    {
        $portfolios = Portfolio::find()->all();

        return $this->render('portfolio' , compact('portfolios'));
    }

    /**
     * @return string|\yii\web\Response
     */
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
        return $this->render('signup', compact('model'));

    }

    /**
     * @return string|\yii\web\Response
     */
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

    /**
     * @param $token
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
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

    /**
     * @return string
     */
    public function actionBlog ()
    {
        $where_publish = ['publish_status' => 'publish'];
        $query = Post::find();
        $pages = new Pagination(['totalCount' =>
            $query->where($where_publish)->count() ,
            'defaultPageSize' => Settings::get(Settings::MAX_ITEM_IN_BLOG)]);

        $posts = $query->offset($pages->offset)
            ->where($where_publish)
            ->limit($pages->limit)->all();

        return $this->render('blog' , compact('posts' ,'pages'));

    }

    /**
     * @return string
     */
    public function actionSearch ($search_query)
    {
        $where_publish = ['publish_status' => 'publish'];

        if(Yii::$app->request->isAjax) {
            $this->layout = false;
        }
        $query = Post::find()
            ->where(['OR', ['like' , 'title' , $search_query] ,
                ['like' , 'content' , $search_query]])
            ->andWhere($where_publish);

        $pages = new Pagination(['totalCount' => $query->count() , 'defaultPageSize' => 6]);

        $count = $query->count();

        $posts = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('search' , compact('posts' , 'pages' , 'search_query' , 'count'));

    }

    /**
     * @param $id
     * @return string
     */
    public function actionPageDraft ($id)
    {
        $post = Post::findOne($id);
        if($post->publish_status != 'draft') {
            $this->redirect(['/post/view' , 'id' => $id]);
        }
        return $this->render('page-draft' , compact('post'));
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionPortfolioContent($id)
    {

        if(!Yii::$app->request->isAjax) {
            return $this->redirect(['site/error']);
        }

        $content = Portfolio::getAllImg($id);

        return $this->renderAjax('ajaxportfolio/item-portfolio' , compact('content'));
    }
}
