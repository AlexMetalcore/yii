<?php

namespace backend\controllers;

use backend\helper\HelperImgCompression;
use backend\models\Post;
use backend\models\Settings;
use Yii;
use backend\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Class UserController
 * @package backend\controllers
 */
class UserController extends Controller
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
                        'actions' => ['index' , 'create' , 'update' , 'view' , 'delete'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['moderator' , 'user'],
                        'actions' => ['update' , 'view'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST' , 'DELETE' , 'GET'],
                ],
            ],
        ];
    }

    /**
     * @param User $model
     * @throws \ImagickException
     */
    private function imgSaveUserAvatar(User $model) {
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->createFilePath() && $model->upload_user_avatar) {
                    $filePath = $model->createFilePath();
                    if ($model->upload_user_avatar->saveAs($filePath)) {
                        new HelperImgCompression(\Yii::$app->basePath.'/web/' , $filePath);
                        chmod(\Yii::$app->basePath.'/web/'. $filePath, 0777);
                        $model_post = new Post();
                        $model->user_img = $model_post->setImgThumb($filePath,Settings::get(Settings::WIDTH_IMAGE_RESIZE), Settings::get(Settings::HEIGHT_IMAGE_RESIZE));
                    }
                }
            }
        }
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $user = User::getEnvUsers();

        $dataProvider = new ActiveDataProvider([
            'query' => $user,
            'pagination' => [
                'pageSize' => 3,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \ImagickException
     */
    public function actionCreate()
    {
        $model  = new User();
        $this->imgSaveUserAvatar($model);

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
            if ($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \ImagickException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->imgSaveUserAvatar($model);

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
           }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * @param $id
     * @param bool $move
     * @return bool|string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $move = false , $user = null)
    {

        $model = $this->findModel($id);
        $model->delete();

        if($model->like) {
            foreach ($model->like as $like) {
                if ($like) {
                    $like->delete();
                }
            }
        }
        if(!$move && !$user && Yii::$app->request->isAjax) {
            foreach ($model->post as $post) {
                $user = User::findIdentity(Yii::$app->user->getId())->username;
                if($user) {
                    $post->author_id = Yii::$app->user->getId();
                    $post->save();
                    Yii::$app->session->removeAllFlashes();
                }
            }
            \Yii::$app->session->setFlash('data-move' , 'Статьи перенесены');
            return $this->renderAjax('ajax/messageaftermove');
        }
        else if ($move && !$user && Yii::$app->request->isAjax) {
            foreach ($model->post as $post) {
                $post->delete();
            }
            \Yii::$app->session->setFlash('delete-data-user' , 'Все статьи удалены');
            return $this->renderAjax('ajax/messageaftermove');
        }
        else if ($model->username == $user && Yii::$app->request->isAjax){
            \Yii::$app->session->setFlash('delete-user' , 'Пользователь удален');
            return $this->renderAjax('ajax/messageaftermove');
        }

        if(Yii::$app->request->isPost) {
            return $this->redirect(['index']);
        }
    }

    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Страница пользователя не существует!');
    }
}
