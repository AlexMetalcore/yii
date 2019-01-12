<?php
namespace frontend\components;

use backend\models\Category;
use backend\models\Post;
use yii\base\Widget;

/**
 * Class CategoryWidget
 * @package frontend\components
 */
class CategoryWidget extends Widget
{
    /**
     *
     */
    const TIME_CACHE = 3600;

    /**
     * @var string
     */
    private $limit_popular = 6;

    /**
     * @var
     */
    public $data;
    /**
     * @var
     */
    public $tree;
    /**
     * @var
     */
    public $menuHtml;


    /**
     * @var
     */
    public $count_posts;

    /**
     * @return array
     */
    private function getTree()
    {
        $tree = [];
        foreach ($this->data as $id => &$node) {
            if (!$node['parent_id'])
                $tree[$id] = &$node;
            else
                $this->data[$node['parent_id']]['childs'][$node['id']] = &$node;
        }

        return $tree;
    }

    /**
     * @param $tree
     * @param string $tab
     * @return string
     */
    private function getMenuHtml($tree,$tab = '')
    {
        $str = '';
        foreach ($tree as $category) {
            $str .= $this->catToTemplate($category, $tab);
        }
        return $str;
    }

    /**
     * @param $category
     * @param $tab
     * @return false|string
     */
    private function catToTemplate($category , $tab)
    {
        ob_start();
        $count_posts = $this->count_posts;
        include __DIR__ . '/views/block-category.php';
        return ob_get_clean();
    }

    /**
     *
     */
    public function init()
    {
        parent::init();;
    }

    /**
     * @return string|void
     */
    public function run() {
        $where = ['publish_status' => 'publish'];
        $this->data = Category::find()->indexBy('id')->asArray()->all();
        foreach ($this->data as $category) {
            $posts = Post::find(['id' => $category['id']])->where($where)->all();
            foreach ($posts as $post) {
                if($category['id'] == $post->category_id) {
                    $this->count_posts[$category['title']][] = [
                        'post_id' => $post->id,
                        'post_name' => $post->title,
                    ];
                }
            }
        }
        $popular = Post::find()
            ->where($where)
            ->orderBy('viewed DESC')
            ->limit($this->limit_popular)
            ->all();
        $this->tree = $this->getTree();
        $this->menuHtml = $this->getMenuHtml($this->tree , $this->count_posts);
        $menu = $this->menuHtml;

        return $this->render('block-popular' , compact('menu', 'popular'));
    }
}