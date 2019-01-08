<?php

namespace backend\controllers;

use backend\models\Post;
use Yii;
use backend\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * Class CategoryController
 * @package backend\controllers
 */
class CategoryController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $category = Category::getQueryCategory();

        $dataProvider = new ActiveDataProvider([
            'query' => $category,
            'pagination' => [
                'pageSize' => 3,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $count = Post::find()->where(['category_id' => $id])->count();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'count' => $count,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Category();
        $category  = Category::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', compact('model' , 'category'));
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $category  = Category::find()->where(['!=' , 'id' , $id])->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->parent_id == 0 || empty($model->parent_id)){
                $model->parent_id = 0;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'category' => $category
        ]);
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
        foreach ($this->findModel($id)->posts as $post){
            $model = Post::findOne($post->id);
            if($model){
                $model->publish_status = 'draft';
                $model->save(false);
            }
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return Category|null
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Этой страници не существует');
    }
}
