<?php

namespace controllers;

use models\User;

class UserController
{
    public User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function authenticate(string $userName, string $password)
    {
        $user = $this->model->get($userName, $password);

        return ($user !== false);
    }
}
