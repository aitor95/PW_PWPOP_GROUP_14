<?php

namespace PwPop\Model;


interface UserRepositoryInterface{

    public function save(User $user, string $profile);

    public function login(string $email, string $password): bool;

    public function takeUser(string $email): User;

    public function update(User $user);

    public function takeProducts(): array;

    public function saveProduct(Product $product);

}
