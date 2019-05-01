<?php

namespace PwPop\Model\Services;


use PwPop\Model\UsersRegistered;

class DBConnection
{

    private $repository;

    public function __construct(UsersRegistered $repository)
    {
        $this->repository = $repository;
    }

    public function register()
    {
        $this->repository->register();
    }
}
