<?php
namespace common\models;

use Yii;
use yii\base\Model;


/**
 * Class LoginForm
 * @package common\models
 */
class LoginForm extends Model
{
    /**
     * @var
     */
    public $username;
    /**
     * @var
     */
    public $password;
    /**
     * @var bool
     */
    public $rememberMe = true;

    /**
     * @var bool
     */
    private $_user = false;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Пользователь',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /*Проверка пароля*/
    /**
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Логин/пароль неверные');
            }
        }
    }

    /*Авторизация пользователя*/
    /**
     * @return bool
     */
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

    /**
     * @return bool|User|null
     */
    private function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * @return bool
     */
    public function loginAdmin()
    {
        if ($this->validate() && User::isUserAdmin($this->username)) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

}
