<?php

namespace PwPop\Model;

interface UserRepositoryInterface{

    public function save(User $user, string $profile);

    public function login(string $email, string $password): bool;

}
