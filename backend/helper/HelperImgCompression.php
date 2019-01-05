<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31.12.18
 * Time: 12:08
 */
namespace backend\helper;

/**
 * Class HelperImgCompression
 * @package backend\helper
 */
class HelperImgCompression
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
     * HelperImgCompression constructor.
     * @param $path
     * @throws \ImagickException
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->ImgCompression();
    }


    /**
     * @throws \ImagickException
     */
    private function ImgCompression () {
        $full_path_file = \Yii::$app->basePath.'/web/'.$this->path;
        $img = new \Imagick($full_path_file);
        if (filesize($full_path_file) > 1024*1000) {
            $img->setImageCompression(true);
            $img->setImageCompression(self::PARAMETERS_COMPRESSION);
            $img->setImageCompressionQuality(self::PARAMETERS_QUALITY);
            $img->writeImage($this->path);
        }
        $img->writeImage($this->path);
        chmod($full_path_file, 0777);
        $img->clear();
        $img->destroy();
    }
}