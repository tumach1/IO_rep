<?php
require_once 'Controller.php';
require_once __DIR__.'/../repository/BookRepository.php';

class BooksAPIController extends Controller
{

    public function call()
    {
        $repository = new BookRepository();
        $books = $repository->getAllJsonData();

        echo $books;
    }
}