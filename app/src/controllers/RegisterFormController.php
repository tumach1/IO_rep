<?php
require_once 'Controller.php';
require_once __DIR__.'/../utils/Validator.php';
require_once __DIR__.'/../../Database.php';
require_once __DIR__.'/../repository/ReaderRepository.php';
require_once __DIR__.'/../utils/Mailer.php';
class RegisterFormController extends Controller
{

    public function call()
    {
        $email = $_POST['email'];
        $postalCode = $_POST['kod_pocztowy'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirmedPassword'];
        $pesel = $_POST['pesel'];

        if (!Validator::checkPassword($password, $confirmedPassword)) {
            $this->render("ReaderRegister.php", ['messages' => ["Invalid password"]]);
            die();
        }

        if(!Validator::checkEmailAddress($email) || !Validator::checkPostalCode($postalCode)){
            $this->render("ReaderRegister.php", ['messages' => ["Please correct invalid informations"]]);
            die();
        }

        if (!Validator::checkPesel($pesel)) {
            $this->render("ReaderRegister.php", ['messages' => ["Please correct invalid PESEL"]]);
            die();
        }

        $readerRepository = new ReaderRepository();
        if ($readerRepository->existsByEmailOrPesel($email, $pesel)) {
            $this->render("ReaderRegister.php", ['messages' => ["User already exists"]]);
            die();
        }

        $reader = ReaderRepository::create($_POST['imie'], $_POST['nazwisko'], $_POST['email'], $_POST['pesel'], $_POST['ulica'], $_POST['miejscowosc'], $_POST['kod_pocztowy'], $_POST['password']);

        if(!$reader) {
            die(); //Not created
        }
        Mailer::sendRegistrationConfirmation($_POST['imie'], $_POST['nazwisko'], $_POST['email']);
    }
}