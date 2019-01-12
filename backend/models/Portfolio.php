<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.12.18
 * Time: 2:19
 */

namespace backend\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use common\implement\UploadFileInterfaces;

/**
 * Class Portfolio
 * @package common\models
 */
class Portfolio extends ActiveRecord implements UploadFileInterfaces
{
    /**
     * @var
     */
    public $gallery;

    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::tableName();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title' , 'content'], 'required'],
            ['title', 'unique', 'targetClass' => '\backend\models\Portfolio', 'message' => 'Работа существует'],
            [['gallery'], 'file', 'maxFiles' => 10,  'skipOnEmpty' => true, 'extensions' => 'png, jpg , jpeg , bmp'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Название работы',
            'content' => 'Контент',
            'img'   => 'Фото'
        ];
    }

    /**
     * @return string|UploadedFile
     */
    public function createFilePath ()
    {
        $this->gallery = UploadedFile::getInstances($this, 'gallery');
        return $this->gallery ? $this->gallery : $this->img;
    }


    /**
     * @return string
     */
    public static function getAllImg ($id)
    {
        $img = Portfolio::findOne($id);
        return $img;
    }

    public static function getLastPortfolio ()
    {
        return self::find()->orderBy('id desc')->limit(Settings::get(Settings::COUNT_LAST_PORTFOLIO))->all();
    }
}