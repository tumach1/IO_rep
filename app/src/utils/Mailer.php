<?php

include_once __DIR__ . '/../library/PHPMailer/src/PHPMailer.php';
include_once __DIR__ . '/../library/PHPMailer/src/SMTP.php';
include_once __DIR__ . '/../library/PHPMailer/src/Exception.php';
require_once __DIR__.'/../repository/ReaderRepository.php';
require_once __DIR__.'/../repository/BookRepository.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private static function setupMailer(): PHPMailer {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mailhog';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
        $mail->Port       = 1025;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->CharSet    = 'UTF-8';                        // Unicode for enabling Polish national signs
        $mail->setFrom('Administracja@biblioteka.local', 'Biblioteka GL05');
        $mail->addReplyTo('info@localhost.local', 'Information');
        return $mail;
    }

    private static function sendMail(PHPMailer $mail) {
        try {
            $mail->send();
            echo 'Email message has been sent. Redirecting to the main page soon..';
            echo '<script>setTimeout(function() { window.location.href = "/"; }, 3500);</script>';
        } catch (Exception $e) {
            echo "Email message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    static function sendRegistrationConfirmation($name, $surname, $email): void
    {
        //Mailer setup
        $mail = self::setupMailer();

        //Recipients
        $nameAndSurname = $name . ' ' . $surname;
        $mail->addAddress($email, $nameAndSurname);      //Add a recipient

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Potwierdzenie rejestracji w systemie bibliotecznym';
        $mail->Body = '
                            <title>Witamy</title>
                            <body>
                                <p>Witaj '.$name.'!<br> 
                                Potwierdzamy założenie konta w naszym serwisie.
                                Miłej lektury życzy zespół biblioteki!</p>
                                
                                <p>Administratorem osobowym Pana/-i danych jest bibilioteka gr lab 05</p>
                            </body>
                            ';
        $mail->AltBody = "Witaj ".$name."!\r\nPotwierdzamy założenie konta w naszym serwisie.\r\n
                                Miłej lektury życzy zespół biblioteki!\r\n
                                Administratorem osobowym Pana/-i danych jest bibilioteka gr lab 05";

        self::sendMail($mail);
    }

    static function sendPasswordResetMessage($readerId): void
    {
        //Prepare data
        $recipient =  (new ReaderRepository)->getById($readerId);
        $passwordResetTuple = (new PasswordResetTupleRepository())->getByReaderId($readerId);
        $url = 'https://localhost/NewPassword?token='.$passwordResetTuple->getToken();

        //Mailer setup
        $mail = self::setupMailer();

        //Recipients
        $nameAndSurname = $recipient->getName() . ' ' . $recipient->getSurname();
        $mail->addAddress($recipient->getEmail(), $nameAndSurname);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Resetowanie hasła';
        $mail->Body = '
                        <title>Witamy</title>
                        <body>
                            <p>Witaj '.$recipient->getName().'!<br> 
                                Do naszego systemu wpłynął wniosek o zresetowanie hasła. <br>
                                Jeśli chce Pan/-i zresetować hasło, proszę kliknąć w ten
                                <a href="'.$url.'">
                                link
                                </a>.
                            </p>   
                            <p>Administratorem osobowym Pana/-i danych jest bibilioteka gr lab 05</p>
                        </body>
                            ';
        $mail->AltBody = "Witaj ".$recipient->getName()."!\r\nPotwierdzamy założenie konta w naszym serwisie.\r\n
                                Miłej lektury życzy zespół biblioteki!\r\n
                                Administratorem osobowym Pana/-i danych jest bibilioteka gr lab 05";

    self::sendMail($mail);
    }

    public static function sendBorrowNotify($readerId, $bookId)
    {
        //Prepare data
        $recipient = (new ReaderRepository)->getById($readerId);
        $book = (new BookRepository())->getById($bookId);
        $text = '';
        foreach ($book->getAuthors() as $author){
            $text .= $author->getFirstName() . ' ' . $author->getSurname() . ' ';
        }
        $text .= '\''.$book->getTitle().'\'.';

        //Mailer setup
        $mail = self::setupMailer();

        //Recipients
        $nameAndSurname = $recipient->getName() . ' ' . $recipient->getSurname();
        $mail->addAddress($recipient->getEmail(), $nameAndSurname);     //Add a recipient

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Potwierdzenie wypożyczenia';
        $mail->Body = '
                        <title>Witamy</title>
                        <body>
                            <p>Witaj '.$recipient->getName().'!<br> 
                                Potiwerdzamy, że zapomocą aplikacji internetowej wypożyczył Pan\-i
                                książkę<br><em>'.$text.'</em><br>Pozycja oczekuje na odbiór.<br>
                                Miłej lektury życzy zespół biblioteki.
                            </p>   
                            <p>Administratorem osobowym Pana/-i danych jest bibilioteka gr lab 05</p>
                        </body>
                            ';
        $mail->AltBody = "Witaj ".$recipient->getName()."!\r\nPotwierdzamy założenie konta w naszym serwisie.\r\n
                                Miłej lektury życzy zespół biblioteki!\r\n
                                Administratorem osobowym Pana/-i danych jest bibilioteka gr lab 05";

        self::sendMail($mail);
    }
}