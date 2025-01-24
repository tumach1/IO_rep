<?php
require_once 'Controller.php';

class OrdersController extends Controller
{

    public function call()
    {
        $this->render('orders.php');
    }
}