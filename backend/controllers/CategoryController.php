<?php

namespace backend\controllers;

use Yii;
use backend\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class CategoryController
 * @package backend\controllers
 */
class CategoryController extends AppController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return parent::behaviors();
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
        $count = count($this->findModel($id)->posts);

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
        $category = Category::find()->all();

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
        $category  = $model::find()->where(['!=' , 'id' , $id])->all();

        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->parent_id)){
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
        $model_category = $this->findModel($id);
        foreach ($model_category->posts as $post){
            if($post){
                $post->publish_status = 'draft';
                $post->save(false);
            }
        }
        $model_category->delete();
        if (Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash('delete_category' , 'Категория удалена');
        }
        else {
            return $this->redirect(['index']);
        }
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
