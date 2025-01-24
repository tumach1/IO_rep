<?php

require_once 'Controller.php';
require_once __DIR__.'/../repository/ReaderRepository.php';
require_once __DIR__.'/../repository/PasswordResetTupleRepository.php';
require_once __DIR__.'/../utils/Mailer.php';
require_once __DIR__.'/../utils/Validator.php';

class ResetPasswordController extends Controller
{

    #[\Override] public function call()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $this->render('resetPassword.php');
        }
        else if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if( isset($_POST['email']) ){
                $email = $_POST['email'];

                if (!Validator::checkEmailAddress($email))
                {
                    $this->render("resetPassword.php", ['messages' => ["Wrong email address"]]);
                    die();
                }

                $repo = new ReaderRepository();
                $reader = $repo->getByEmail($email);
                PasswordResetTupleRepository::create($reader->getId());
                Mailer::sendPasswordResetMessage($reader->getId());
            }
        }
    }
}