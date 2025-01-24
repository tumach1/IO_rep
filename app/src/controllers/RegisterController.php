<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'Controller.php';


class RegisterController extends Controller
{

    public function call()
    {
        $this->render('ReaderRegister.php');
    }
}