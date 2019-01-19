<?php
namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\implement\UploadFileInterfaces;
use yii\web\UploadedFile;
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface , UploadFileInterfaces
{
    /**
     * @var string
     */
    public $upload_user_avatar;
    /**
     *
     */
    const ROLE_ADMIN = 20;
    /**
     *
     */
    const ROLE_MODERATOR = 15;
    /**
     *
     */
    const ROLE_USER = 10;
    /**
     *
     */
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN , self::ROLE_MODERATOR]],
            [['username', 'about'], 'trim'],
            [['username', 'email', 'password'] , 'required'],
            ['username', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'Пользователь существует'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'E-mail существует'],
            [['upload_user_avatar'], 'file', 'extensions' => 'png, jpg , jpeg , bmp'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'username' => 'Автор',
            'password' => 'Пароль',
            'email' => 'E-mail',
            'about' => 'О авторе',
            'count_post' => 'Количество статтей автора',
            'status' => 'Статус'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {

        return static::findOne(['id' => $id, 'status' => [self::ROLE_USER, self::ROLE_ADMIN , self::ROLE_MODERATOR]]);

    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" не реализует интерфейс.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username'  => $username, 'status' => [self::ROLE_USER, self::ROLE_ADMIN , self::ROLE_MODERATOR]]) ? static::findOne(['username'  => $username, 'status' => [self::ROLE_USER, self::ROLE_ADMIN , self::ROLE_MODERATOR]]) : static::findOne(['email'  => $username, 'status' => [self::ROLE_USER, self::ROLE_ADMIN , self::ROLE_MODERATOR]]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString(32);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasMany(Post::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLike()
    {
        return $this->hasMany(LikePosts::class, ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getEnvUsers() {

        return User::find();

    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function getAllUser() {

        return User::find()->all();

    }

    /**
     * @param $username
     * @return bool
     */
    public static function isUserAdmin($username)
    {
        if (static::findOne(['username' => $username, 'status' => [self::ROLE_ADMIN , self::ROLE_MODERATOR , self::ROLE_USER]]) || static::findOne(['email' => $username, 'status' => [self::ROLE_ADMIN , self::ROLE_MODERATOR , self::ROLE_USER]])) {
            return true;
        }
        else {
            Yii::$app->session->setFlash('error' , 'Вы не имеете доступа в админ панель');
            return false;
        }
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDate()
    {
        \Yii::$app->formatter->locale = 'ru-RU';
        return \Yii::$app->formatter->asDate($this->created_at);
    }

    /**
     * @return mixed
     */
    public static function getLastRegisteredUser()
    {
        return self::find()->orderBy('created_at desc')->limit(Settings::get(Settings::COUNT_LAST_USER_REGISTERED))->all();
    }

    /**
     * @return mixed|string
     */
    public function createFilePath ()
    {
        $this->upload_user_avatar = UploadedFile::getInstance($this, 'upload_user_avatar');
        return $this->upload_user_avatar  ? 'images/' . uniqid() . '.' . $this->upload_user_avatar->extension : $this->user_img;
    }

    public function getAvatar()
    {
        return $this->user_img ?'../../admin/'.$this->user_img : 'http://www.gravatar.com/avatar?d=mm&f=y&s=50';
    }
}
