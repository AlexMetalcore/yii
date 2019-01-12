<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.08.18
 * Time: 0:36
 */

namespace common\components\rbac;
use yii\rbac\Rule;
use yii\helpers\ArrayHelper;
use backend\models\User;

class UserRoleRule extends Rule
{
    public $name = 'userRole';
    public function execute($user, $item, $params) {
        $user = ArrayHelper::getValue($params, 'user', User::findOne($user));
        if ($user) {
            $role = $user->status;
            if ($item->name === 'admin') {
                return $role == User::ROLE_ADMIN;
            }
            else if ($item->name === 'user') {
                return $role == User::ROLE_ADMIN || $role == User::ROLE_USER;
            }
            else if ($item->name === 'moderator'){
                return $role == User::ROLE_ADMIN || $role == User::ROLE_MODERATOR;
            }
        }
        return false;
    }
}