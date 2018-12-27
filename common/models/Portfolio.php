<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.12.18
 * Time: 2:19
 */

namespace common\models;


use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class Portfolio
 * @package common\models
 */
class Portfolio extends ActiveRecord
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
    public function createFilePath (){
        $this->gallery = UploadedFile::getInstances($this, 'gallery');
        return $this->gallery ? $this->gallery : $this->img;
    }

    /**
     * @return array
     */
    public function getMainImg () {
        $img = unserialize(Portfolio::findOne($this->id)->img);
        return count($img) > 2 ? array_shift($img) : $img[0];
    }

    /**
     * @return string
     */
    public function getAllImg () {
        $img = unserialize(Portfolio::findOne($this->id)->img);
        return $img;
    }
}