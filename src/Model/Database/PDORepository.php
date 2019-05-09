<?php

namespace PwPop\Model\Database;

use PDO;
use PwPop\Model\User;
use PwPop\Model\UserRepositoryInterface;

final class PDORepository implements UserRepositoryInterface{

    /** @var Database */
    private $database;

    /**
     * PDORepository constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function save(User $user) {

        $statement = $this->database->connection->prepare(
            "INSERT INTO user(email, password, birth_date, name, username, phone, created_at, updated_at) 
                        values(:email, :password, :birth_date, :name, :username, :phone, :created_at, :updated_at)");

        $email = $user->getEmail();
        $password = $user->getPassword();
        $birth_date = $user->getBirthDate();
        $name = $user->getName();
        $username = $user->getUsername();
        $phone = $user->getPhone();
        $createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $user->getUpdatedAt()->format('Y-m-d H:i:s');

        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);
        $statement->bindParam('birth_date', $birth_date, PDO::PARAM_STR);
        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->bindParam('username', $username, PDO::PARAM_STR);
        $statement->bindParam('phone', $phone, PDO::PARAM_STR);
        $statement->bindParam('created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }

    public function login(string $email, string $password): bool
    {

        $info = $this->database->connection->query('SELECT * FROM user');
        $data = $info->fetchAll();


        $registered = false;
        for ($i=0; $i < sizeof($data) ; $i++) {
            if($email == $data[$i]['email'] && $password == $data[$i]['password']){
                $registered = true;
            }
        }

        return $registered;
    }
}
