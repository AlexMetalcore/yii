<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => 'Пользователь',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /*Проверка пароля*/
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Логин/пароль неверные');
            }
        }
    }

    /*Авторизация пользоваиеля*/
    public function login()
    {
        if ($this->validate()) {
            if ($this->rememberMe) {
                $user = $this->getUser();
                $user->generateAuthKey();
                $user->save();
            }
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /*Проверка на существования пользователя*/

    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
