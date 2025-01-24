<?php

require_once 'Controller.php';
require_once __DIR__.'/../repository/PasswordResetTupleRepository.php';

class NewPasswordController extends Controller
{

    #[\Override] public function call()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ( isset($_GET['token']) && isset($_COOKIE['resetPasswordToken']) ){
                $token = $_GET['token'];
                $sessionToken = $_COOKIE['resetPasswordToken'];
                $tuple = (new PasswordResetTupleRepository())->getByToken($token);
                if(!empty($tuple) && $sessionToken == $tuple->getSessionToken()) {
                    setcookie('resetPasswordToken', '', -1, '/', 'localhost', true);
                    $this->render('newPassword.html');
                }
                else{
                    $this->render('home.php');
                }
            }
            else {
              $this->render('home.php');
            }
        }
        else if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $token = $_GET['token'];
            $tuple = (new PasswordResetTupleRepository())->getByToken($token);
            if($tuple != null && $tuple->getTimestamp() >= time() && isset($_POST['password'])){
                $stmt = Database::get()->prepare('
                UPDATE czytelnicy
                SET hash = :hash
                WHERE czytelnik_id = :id
                ');
                $hash = password_hash($_POST['password'], PASSWORD_ARGON2ID);
                $stmt->bindParam(':hash', $hash);
                $readerId = $tuple->getReaderId();
                $stmt->bindParam(':id', $readerId);
                $stmt->execute();

                $stmt = Database::get()->prepare('
                DELETE FROM resetowanie_hasel
                WHERE czytelnik_id = :id
                ');
                $stmt->bindParam(':id', $readerId);
                $stmt->execute();

                echo 'Password changed';
            }
            else{
                $this->render('home.php');
            }
        }
    }
}