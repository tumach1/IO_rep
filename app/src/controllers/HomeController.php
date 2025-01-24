<?php
require_once 'Controller.php';

class HomeController extends Controller
{

    public function call()
    {
        $this->render('home.php');
    }
}