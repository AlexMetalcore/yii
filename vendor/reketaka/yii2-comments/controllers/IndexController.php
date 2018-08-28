<?php

namespace reketaka\comments\controllers;

use Yii;
use yii\web\Controller;
use reketaka\comments\models\Comments;

class IndexController extends Controller{

    public function actionSend(){

        $module = Yii::$app->getModule('comments');

        /**
         * Если запрещено комментировать гостя, переадресацию делаем обратно откуда пришел
         */
        if(Yii::$app->user->isGuest && !$module->guestCanComment){
            return $this->redirect(Yii::$app->request->referrer?:Yii::$app->homeUrl);
        }

        $model = new Comments([
            'scenario'=>Yii::$app->user->isGuest?Comments::SCENARIO_FORM_GUEST:Comments::SCENARIO_FORM_USER
        ]);

        if($model->load(Yii::$app->request->post()) && $model->validate()){



            $model->save();
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }

        Yii::$app->session->setFlash(Comments::$errorKeyName, $model->getErrors());

        return $this->redirect(Yii::$app->request->referrer?:Yii::$app->homeUrl);

    }

}