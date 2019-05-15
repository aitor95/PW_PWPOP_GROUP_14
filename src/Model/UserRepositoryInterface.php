<?php

namespace PwPop\Model;

use PwPop\Model\User;

interface UserRepositoryInterface{

    public function save(User $user, string $profile);

    public function login(string $email, string $password): bool;

    public function takeUser(string $email): User;

}
