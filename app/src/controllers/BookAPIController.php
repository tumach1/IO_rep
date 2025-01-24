<?php
require_once 'Controller.php';
require_once __DIR__.'/../repository/BookRepository.php';
class BookAPIController extends Controller
{

    public function call()
    {
        if(!isset($this->routeData['id'])) {
            die("Missing id");
        }

        $repository = new BookRepository();
        $book = $repository->getJsonData($this->routeData['id']);

        echo $book;
        exit();
    }
}