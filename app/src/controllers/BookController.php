<?php
require_once 'Controller.php';

class BookController extends \Controller
{

    public function call()
    {
        $this->render('book.php', ['id' => $this->routeData['id']]);
    }
}