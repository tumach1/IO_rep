<?php

require_once 'Controller.php';
abstract class SessionController extends Controller
{

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        session_start();
    }

    protected function isReader(): bool
    {
        return isset($_SESSION['Reader']);
    }

    protected function isWorker(): bool
    {
        return isset($_SESSION['Worker']);
    }

    protected function getReader()
    {
        if($this->isReader())
        {
            return $_SESSION['Reader'];
        }
        return null;
    }

    protected function getWorker()
    {
        if($this->isWorker())
        {
            return $_SESSION['Worker'];
        }
        return null;
    }
}