<?php
require_once 'Controller.php';
class IndexController extends Controller
{

    public function call()
    {
        $this->render('index.html');
    }
}