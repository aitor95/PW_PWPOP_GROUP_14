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

    public function save(User $user, string $profile) {

        $statement = $this->database->connection->prepare(
            "INSERT INTO user(email, password, birth_date, name, username, phone, created_at, updated_at, profileImg) 
                        values(:email, :password, :birth_date, :name, :username, :phone, :created_at, :updated_at, :profileImg)");

        $email = $user->getEmail();
        $password = $user->getPassword();
        $passcrypted = md5($password);
        $birth_date = $user->getBirthDate();
        $name = $user->getName();
        $username = $user->getUsername();
        $phone = $user->getPhone();
        $createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $user->getUpdatedAt()->format('Y-m-d H:i:s');
        $profileImg = $profile;

        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $passcrypted, PDO::PARAM_STR);
        $statement->bindParam('birth_date', $birth_date, PDO::PARAM_STR);
        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->bindParam('username', $username, PDO::PARAM_STR);
        $statement->bindParam('phone', $phone, PDO::PARAM_STR);
        $statement->bindParam('created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updated_at', $updatedAt, PDO::PARAM_STR);
        $statement->bindParam('profileImg', $profileImg, PDO::PARAM_STR);

        $statement->execute();
    }

    public function login(string $email, string $password): bool
    {

        $info = $this->database->connection->query('SELECT * FROM user');
        $data = $info->fetchAll();

        $registered = false;
        $passwordcrypted = md5($password);
        for ($i=0; $i < sizeof($data) ; $i++) {
            if($email == $data[$i]['email'] && $passwordcrypted == $data[$i]['password']){
                $registered = true;
            }
        }

        return $registered;
    }

    public function isRegistered(string $email, string $username): int{

        $info = $this->database->connection->query('SELECT * FROM user');
        $data = $info->fetchAll();

        $registered = 0;
        for ($i=0; $i < sizeof($data) ; $i++) {
            if($email == $data[$i]['email']){
                $registered+=3;
            }elseif($username == $data[$i]['username']){
                $registered++;
            }
        }

        return $registered;

    }
}
