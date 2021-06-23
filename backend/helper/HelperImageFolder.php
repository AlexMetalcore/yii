<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.19
 * Time: 1:46
 */

namespace backend\helper;

use backend\models\Portfolio;
use backend\models\Post;
use backend\models\Settings;
use backend\models\User;

/**
 * Class HelperImageFolder
 * @package backend\helper
 */
class HelperImageFolder
{
    private $pattern = '/\.(jpg)|(jpeg)|(bmp)|(png)/';
    /**
     * @var string
     */
    private $path_static;
    /**
     * @var array
     */
    public $array_image;
    /**
     * @var string
     */
    public $path;

    /**
     * HelperImageFolder constructor.
     * @param Portfolio $portfolio
     * @param Post $post
     */
    public function __construct()
    {
        $this->path = \Yii::$app->basePath . '/web/images/';
        /*transfer variable to view in any controller*/
        $this->array_image = $this->getTrashArrayPhoto();
        $this->path_static = \Yii::$app->basePath . '/web/images/staticimg/';

    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public static function removeImgTags($str)
    {
        return preg_replace('#<img[^>]*>#i', '', $str);
    }

    /**
     * @param $portfolio
     * @param $posts
     * @return array
     */
    private function getTrashArrayPhoto()
    {
        $imgportfolio = [];
        $imgpost = [];
        $imguser = [];
        $imgpostthumb = [];
        $onlyimg = [];
        $allimg = scandir($this->path);

        $portfolio = Portfolio::find()->all();
        $posts = Post::find()->all();
        $users = User::find()->all();

        foreach ($allimg as $img) {
            if (preg_match($this->pattern, $img)) {
                $onlyimg[] = $img;
            }
        }

        foreach ($portfolio as $img) {
            $imgs = explode(',', $img->img);
            foreach ($imgs as $imgitem) {
                $imgportfolio[] = basename($imgitem);
            }
        }
        foreach ($posts as $post_img) {
            $imgpost[] = basename($post_img->img);
            $imgpostthumb[] = basename($post_img->thumb_img);
            $allimginpost = array_merge($imgpost, $imgpostthumb);
        }

        foreach ($users as $user) {
            $imguser[] = basename($user->user_img);
        }

        $global_array_img = array_merge($imgportfolio, $allimginpost, $imguser);
        $delete_img = array_diff($onlyimg, $global_array_img);

        return $delete_img;
    }

    /**
     * @return string
     */
    public function deleteTrashImg()
    {
        $delete_img = $this->getTrashArrayPhoto();
        if ($delete_img) {
            foreach ($delete_img as $img) {
                $file_delete = $this->path . $img;
                if (file_exists($file_delete)) {
                    unlink($file_delete);
                    \Yii::$app->session->setFlash('success', 'Старые картинки удалены');
                }
            }
        }
    }


    /**
     * @return array
     */
    public function staticFolderImage()
    {
        $get_all_img = scandir($this->path_static);
        $onlyimgs = [];
        foreach ($get_all_img as $img) {
            if (preg_match($this->pattern, $img) && filesize($this->path_static . $img) > Settings::get(Settings::FILESIZE_FILE_COMPRESSION)) {
                $onlyimgs[] = $this->path_static . $img;
            }
        }
        return $onlyimgs;
    }

    /**
     * @return array
     */
    public function getAllImages()
    {
        $dir_images = scandir($this->path);
        $dir_static_img = scandir($this->path_static);
        $img_src = [];
        $img_src_static = [];

        setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');

        foreach ($dir_images as $img) {
            if (preg_match($this->pattern, $img)) {
                $img_src['/admin/images/' . $img] = strftime("%B %d, %Y", date(filemtime(\Yii::$app->basePath . '/web/images/' . $img)));
            }
        }

        arsort($img_src);

        foreach ($dir_static_img as $img) {
            if (preg_match($this->pattern, $img)) {
                $img_src_static['/admin/images/staticimg/' . $img] = strftime("%B %d, %Y", date(filemtime(\Yii::$app->basePath . '/web/images/staticimg/' . $img)));
            }
        }

        arsort($img_src_static);

        return array_merge($img_src, $img_src_static);
    }
}