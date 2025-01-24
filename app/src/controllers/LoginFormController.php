<?php
require_once 'Controller.php';
require_once __DIR__.'/../repository/ReaderRepository.php';
require_once __DIR__.'/../repository/WorkerRepository.php';
require_once __DIR__.'/../utils/Validator.php';
class LoginFormController extends Controller
{

    public function call()
    {
        if(!(isset($_POST['email']) && isset($_POST['password']))){
            $this->render("Login.php", ['messages' => ["Please provide all needed information"]]);
            exit();
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $isWorker = isset($_POST['isWorker']);
        if($isWorker)
        {
            $workerRepository = new WorkerRepository();
            $worker = $workerRepository->getByEmail($email);

            if (!$worker) {
                $this->render("Login.php", ['messages' => ["Invalid information for worker"]]);
                unset($_POST['email']);
                unset($_POST['password']);
                exit();
            }

            $hash = $worker->getHash();

            if (!(password_verify($password, $hash) == 1)) {
                $this->render("Login.php", ['messages' => ["Invalid information for worker"]]);
                unset($_POST['email']);
                unset($_POST['password']);
                exit();
            }

            session_start();
            $_SESSION['Worker'] = $worker->getId();

            $url = "https://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/");
            die();
        }
        else
        {
            $readerRepository = new ReaderRepository();
            $reader = $readerRepository->getByEmail($email);

            if(!$reader) {
                $this->render("Login.php", ['messages' => ["Invalid information"]]);
                unset($_POST['email']);
                unset($_POST['password']);
                exit();
            }

            $hash = $reader->getHash();


            if(!(password_verify($password, $hash) == 1)){
                $this->render("Login.php", ['messages' => ["Invalid information"]]);
                unset($_POST['email']);
                unset($_POST['password']);
                exit();
            }

            if(password_needs_rehash($hash, PASSWORD_ARGON2ID)){
                $reader->updateHash($password);
            }

            session_start();
            $_SESSION['Reader'] = $reader->getId();

        }

        $url = "https://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
    }
}