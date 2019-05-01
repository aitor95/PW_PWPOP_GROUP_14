<?php

    namespace PwPop\Model;

    use PDO;

    class UsersRegistered{

        public function register() {

            try {

                //Farem que ens enviin el usuari que volem registrar i el registrarem
                /*
               $db = new PDO('mysql:host=localhost;dbname=ac2;', 'root', 'root');
               $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
               $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               $sentencia = $db->prepare("INSERT INTO task(title,content,created_at,updated_at) VALUES (:title,:content,:created,:updated)");
               $sentencia->bindParam(':title', $task->getTitle(),PDO::PARAM_STR);
               $sentencia->bindParam(':content', $task->getContent(),PDO::PARAM_STR);
               $sentencia->bindParam(':created', $task->getCreatedAt()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
               $sentencia->bindParam(':updated', $task->getUpdatedAt()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
               $sentencia->execute();*/

            } catch (PDOException $e) {
                $error = "An unexpected error has occurred fetching the available tasks, please try it again later";
            }

        }

    }