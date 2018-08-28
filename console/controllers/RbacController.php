<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.08.18
 * Time: 0:21
 */
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\components\rbac\UserRoleRule;
use yii\rbac\DbManager;

class RbacController extends Controller
{
    public function actionInit() {
        $auth = Yii::$app->authManager;
        //$auth->removeAll();
        $dashboard = $auth->createPermission('admin');
        $dashboard->description = 'Админ панель';
        $auth->add($dashboard);
        //Включаем наш обработчик
        $rule = new UserRoleRule();
        $auth->add($rule);
        //Добавляем роли
        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $user->ruleName = $rule->name;
        $auth->add($user);;
        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $admin->ruleName = $rule->name;
        $auth->add($admin);
    }
}
