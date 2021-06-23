<?php

namespace common\repositories;

use backend\models\Post;
use yii\base\BaseObject;
use yii\data\Pagination;

class PostRepository implements PostRepositoryInterface
{

    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function create(Post $post)
    {
        // TODO: Implement create() method.
    }

    public function save(Post $user)
    {
        // TODO: Implement save() method.
    }

    public function remove(Post $user)
    {
        // TODO: Implement remove() method.
    }

    public function getAll($condition = null, $searchQuery = null)
    {
        $query = Post::find()
            ->where(['OR', ['like', 'title', $searchQuery],
                ['like', 'content', $searchQuery]])
            ->andWhere($condition);

        $pages = new Pagination(['totalCount' => $query->count(), 'defaultPageSize' => 6]);

        $count = $query->count();

        $posts = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
    }
}