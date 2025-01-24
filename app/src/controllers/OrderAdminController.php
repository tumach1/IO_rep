<?php
require_once 'Controller.php';
class OrderAdminController extends Controller
{

    public function call()
    {
        $this->render('ordersAdmin.php');
    }
}