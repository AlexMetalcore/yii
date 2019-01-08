<?php

namespace backend\controllers;

use backend\models\Category;
use Yii;
use common\models\User;
use backend\models\Post;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\LikePosts;
use backend\helper\HelperImgCompression;
use yii\web\ForbiddenHttpException;

/**
 * Class PostController
 * @package backend\controllers
 */
class PostController extends Controller
{
    /**
     * @var integer
     */
    const WIDTH_IMAGE_RESIZE = 300;
    /**
     * @var integer
     */
    const HEIGHT_IMAGE_RESIZE = 200;
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
                    'delete' => ['GET' , 'POST' , 'DELETE'],
                ],
            ],
        ];
    }

    /**
     * @param $id
     * @return Post|null
     * @throws NotFoundHttpException
     */
    private function findModel($id) {

        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашеваемой страници не существует.');
    }

    /**
     * @param Post $model
     * @throws \ImagickException
     */
    private function handlePostSave(Post $model) {
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->createFilePath() && $model->upload) {
                    $filePath = $model->createFilePath();
                    if ($model->upload->saveAs($filePath)) {
                        new HelperImgCompression($filePath);
                        $model->thumb_img = $this->createImgThumb($filePath);
                        $model->img = $filePath;
                    }
                }
            }
        }
    }

    /**
     * @param $filePath
     * @return string
     * @throws \ImagickException
     */
    private function createImgThumb($filePath) {
        $thumb = new \Imagick($filePath);
        $thumb->resizeImage(self::WIDTH_IMAGE_RESIZE, self::HEIGHT_IMAGE_RESIZE, \Imagick::FILTER_LANCZOS,1);
        $thumb_path = 'images/'.uniqid().basename($thumb->getImageFilename());
        $thumb->writeImage($thumb_path);
        chmod(\Yii::$app->basePath.'/web/'.$thumb_path, 0777);
        return $thumb_path;
    }
    /**
     * @return array
     */
    public function filters()
    {
        return [[
                'COutputCache',
                'duration'=> 60,
                'varyByParam'=>['id'],
            ],];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Post::find(),
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

        return $this->render('view', compact('model'));
    }


    /**
     * @return string|\yii\web\Response
     * @throws \ImagickException
     */
    public function actionCreate()
    {
        $model = new Post();
        $this->handlePostSave($model);
        $category = Category::getAllCategory();
        $authors = User::getAllUser();

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->attributes['img']) {
                $model->addError('upload' , 'Ошибка загрузки файла');
            }
            else {
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create',compact('model' , 'category' ,'authors'));
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
        $this->handlePostSave($model);
        $category = Category::getAllCategory();
        $authors = User::getAllUser();

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->attributes['img']) {
                $model->addError('upload' , 'Ошибка загрузки файла');
            }
            else {
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', compact('model' , 'category' ,'authors'));
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model_post = $this->findModel($id);
        $model_post->delete();
        if (Yii::$app->request->isAjax) {
            echo 'Запись удалена';
        }
        else {
            return $this->redirect(['index']);
        }
        $model = LikePosts::getOnePost($id);
        if($model) {
            $model->delete();
        }
    }
}
