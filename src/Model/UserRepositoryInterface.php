<?php

namespace PwPop\Model;

interface UserRepositoryInterface{

    public function save(User $user);

    public function login(string $email, string $password): bool;

}
