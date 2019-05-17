<?php

namespace PwPop\Model\Database;

use PDO;
use PwPop\Model\User;
use PwPop\Model\UserRepositoryInterface;
use PwPop\Model\Product;
use DateTime;


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
        $deletedAcc = false;
        $passwordcrypted = md5($password);

        for ($i=0; $i < sizeof($data) ; $i++) {
            if($email == $data[$i]['email'] && $passwordcrypted == $data[$i]['password']){
                $registered = true;
                if($data[$i]['is_active'] == 0){
                    $deletedAcc = true;
                    $registered = false;
                    $_SESSION['error'] = 'Account Deleted!';
                }
            }
        }

        if($registered==false && $deletedAcc == false){
            $_SESSION['error'] = 'User or Email Invalid!';
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
            }
            if($username == $data[$i]['username']){
                $registered++;
            }
        }

        return $registered;

    }

    public function takeUser(string $email): User
    {

        $query = "SELECT * FROM user WHERE email=\"" . $email . "\"";
        $stmt = $this->database->connection->prepare($query);
        $stmt->execute();
        $data = $stmt->fetch();

        $datetime = new DateTime();
        $newDate = $datetime->createFromFormat('Y/M/D H:i:s', $data['created_at']);

        $datetime2 = new DateTime();
        $newDate2 = $datetime2->createFromFormat('Y/M/D H:i:s', $data['updated_at']);

        $user = new User(
            $data['email'],
            $data['password'],
            $data['birth_date'],
            $data['name'],
            $data['username'],
            $data['phone'],
            $datetime,
            $datetime2,
            $data['profileImg'],
            $data['is_active']
        );

        return $user;
    }

    public function update(User $user, int $mode){

        $email = $user->getEmail();
        $username = $user->getUsername();
        $password= $user->getPassword();
        $passcrypted = md5($password);
        if($mode==2){
            $passcrypted = $password;
        }
        $birth_date = $user->getBirthDate();
        $name = $user->getName();
        $phone = $user->getPhone();
        $updatedAt = $user->getUpdatedAt()->format('Y-m-d H:i:s');
        $profileImg = $user->getProfileImg();
        $is_active = $user->getisActive();

        $query = "UPDATE user SET email=\"" . $email . "\", password=\"" . $passcrypted . "\", birth_date=\"" . $birth_date . "\", 
        name=\"" . $name . "\", phone=\"" . $phone . "\", profileImg =\"" . $profileImg . "\", updated_at=\"" . $updatedAt . "\",  
        is_active =\"" . $is_active . "\" WHERE username=\"" . $username . "\"";

        $statement = $this->database->connection->prepare($query);

        $statement->execute();

    }

    public function takeProducts(): array{

        $info = $this->database->connection->query('SELECT * FROM product');
        $data = $info->fetchAll();

        $products = [];
        for ($i=0; $i < sizeof($data) ; $i++) {
            array_push($products,$data[$i]);
        }

        return $products;


    }

    public function saveProduct(Product $product) {

        $statement = $this->database->connection->prepare(
            "INSERT INTO product(id,username, title, description, price, productImg, category,is_active) 
                        values(:id, :username, :title, :description, :price, :productImg, :category, 1)");

        $id = $product->getId();
        $username = $product->getUsername();
        $title = $product->getTitle();
        $description = $product->getDescription();
        $price = $product->getPrice();
        $productImg = $product->getProductImg();
        $category = $product->getCategory();

        $statement->bindParam('id', $id, PDO::PARAM_STR);
        $statement->bindParam('username', $username, PDO::PARAM_STR);
        $statement->bindParam('title', $title, PDO::PARAM_STR);
        $statement->bindParam('description', $description, PDO::PARAM_STR);
        $statement->bindParam('price', $price, PDO::PARAM_STR);
        $statement->bindParam('productImg', $productImg, PDO::PARAM_STR);
        $statement->bindParam('category', $category, PDO::PARAM_STR);

        $statement->execute();
    }

    public function deleteProducts(string $username){

        $query = "UPDATE product SET is_active=0 WHERE username=\"" . $username . "\"";

        $statement = $this->database->connection->prepare($query);

        $statement->execute();

    }
}
