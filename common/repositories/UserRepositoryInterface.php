<?php

namespace common\repositories;

use backend\models\User;

interface UserRepositoryInterface
{
    public function get(int $id);

    public function create(User $user);

    public function save(User $user);

    public function remove(User $user);
}