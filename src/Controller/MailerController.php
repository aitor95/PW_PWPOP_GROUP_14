<?php

namespace PwPop\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PwPop\Model\User;


class MailerController
{

    public function buyMail(User $buyer, User $seller){

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer;

        try {
            //Server settings
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'pwpop14@gmail.com';                     // SMTP username
            $mail->Password   = 'otqnrvohhpnanech';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('pwpop14@gmail.com', 'AdminPwPop');
            $mail->addAddress($seller->getEmail(), $seller->getUsername());

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Purchase of Your Product';
            $mail->Body    = '<p>Hi, here is your new offer:</p>
                                <p><b>Username Buyer:</b>'.$buyer->getUsername().'</p>
                                <p><b>Phone Buyer:</b>'.$buyer->getPhone().'</p>';

            $mail->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function confirmationMail(User $user){

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer;

        try {
            //Server settings
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'pwpop14@gmail.com';                     // SMTP username
            $mail->Password   = 'otqnrvohhpnanech';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('pwpop14@gmail.com', 'AdminPwPop');
            $mail->addAddress($user->getEmail(), $user->getUsername());

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Confirm Your Account';
            $mail->Body    = '<p>Hi, here is your confirmation email:</p>
                                <a href="pwpop.test/confirmation?username='.$user->getUsername().'">Confirm Account</a>
                                <p>See You SOON!';

            $mail->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }


}