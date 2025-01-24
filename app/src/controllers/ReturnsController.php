<?php
require_once 'Controller.php';
class ReturnsController extends Controller
{

    public function call()
    {
        $this->render('returns.php');
    }
}