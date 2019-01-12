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
    const PARAMETERS_QUALITY = 60;
    /**
     * @var
     */
    const PARAMETERS_COMPRESSION = 8;

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
        if (filesize($full_path) > 1024*1000) {
            $img->setImageCompression(true);
            $img->setImageCompression(self::PARAMETERS_COMPRESSION);
            $img->setImageCompressionQuality(self::PARAMETERS_QUALITY);
            $img->writeImage($full_path);
        }
        $img->clear();
        $img->destroy();
    }
}