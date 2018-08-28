<?php

namespace reketaka\comments\helpers;

use Yii;
use reketaka\comments\Module;
use yii\base\InvalidConfigException;


class BaseHelper{

    /**
     * Проверяет переданную модель на необходимые условия
     * @param $model
     * @return bool
     * @throws InvalidConfigException
     */
    public static function checkModel($model){
        $module = Yii::$app->getModule('comments');

        if(!is_object($model)){
            $msg = Module::t('errors', 'not_a_model');
            throw new InvalidConfigException($msg);
        }

        if(is_null($model->getBehavior($module->commentBehaviorName))){
            throw new InvalidConfigException(Module::t('errors', 'model_not_have_behavior'));
        }

        if(is_null($id = key($model->getPrimaryKey(true)))){
            throw new InvalidConfigException(Module::t('errors', 'model_dont_have_primary_key'));
        }

        return $model;
    }

}