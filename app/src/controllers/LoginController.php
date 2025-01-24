<?php
require_once 'Controller.php';

class LoginController extends Controller
{

    public function call()
    {
        $this->render("Login.php");
    }
}