<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.01.19
 * Time: 0:12
 */

namespace backend\models;


use common\implement\UploadFileInterfaces;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class Media
 * @package backend\models
 */
class Media extends Model implements UploadFileInterfaces
{
    /**
     * @var string or array photo
     */
    public $media_upload;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['media_upload'], 'file', 'maxFiles' => 20, 'skipOnEmpty' => false ,  'extensions' => 'png, jpg , jpeg , bmp'],
        ];
    }

    /**
     * @return mixed|string
     */
    public function createFilePath ()
    {
        $this->media_upload = UploadedFile::getInstances($this, 'media_upload');
        return $this->media_upload ? $this->media_upload : '';
    }
}