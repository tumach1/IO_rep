<?php
require_once 'SessionController.php';
class LogoutController extends SessionController
{
    public function call()
    {
        session_destroy();
        $url = "https://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/" );
        die();
    }
}