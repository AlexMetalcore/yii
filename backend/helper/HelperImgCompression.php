<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31.12.18
 * Time: 12:08
 */
namespace backend\helper;

use backend\models\Settings;

/**
 * Class HelperImgCompression
 * @package backend\helper
 */
class HelperImgCompression
{
    /**
     * @var string
     */
    private $file_name;
    /**
     * @var string
     */
    private $full_path_file;


    /**
     * HelperImgCompression constructor.
     * @param $full_path_file
     * @param $file_name
     * @throws \ImagickException
     */
    public function __construct($full_path_file , $file_name)
    {
        $this->file_name = $file_name;
        $this->full_path_file = $full_path_file;
        $this->ImgCompression();
    }

    /**
     * @throws \ImagickException
     */
    private function ImgCompression () {
        $full_path = $this->full_path_file.$this->file_name;
        $img = new \Imagick($full_path);
        if (filesize($full_path) > Settings::get(Settings::FILESIZE_FILE_COMPRESSION)) {
            $img->setImageCompression(true);
            $img->setImageCompression(Settings::get(Settings::PARAMETERS_COMPRESSION));
            $img->setImageCompressionQuality(Settings::get(Settings::PARAMETERS_QUALITY));
            $img->writeImage($full_path);
        }
        $img->clear();
        $img->destroy();
    }
}