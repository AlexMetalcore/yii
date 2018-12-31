<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31.12.18
 * Time: 12:08
 */
namespace backend\helper;

class HelperImgUpload
{
    /**
     * @var
     */
    const PARAMETERS_QUALITY = 80;
    /**
     * @var
     */
    const PARAMETERS_COMPRESSION = 8;

    /**
     * @var
     */
    private $path;

    /**
     * HelperImgUpload constructor.
     * @param $path
     * @throws \ImagickException
     */
    public function __construct($path)
    {
        $this->path = $path;
        $img = new \Imagick(\Yii::$app->basePath.'/web/'.$this->path);
        $img->setImageCompression(true);
        $img->setImageCompression(self::PARAMETERS_COMPRESSION);
        $img->setImageCompressionQuality(self::PARAMETERS_QUALITY);
        $img->writeImage($this->path);
        $img->clear();
        $img->destroy();
    }
}