<?php
require_once 'SessionController.php';
require_once __DIR__.'/../repository/OrdersRepository.php';

class OrdersAdminAPIController extends SessionController
{
    public function call(){

    try{
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        return;
    }
    else if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $status_id = $_GET['status_id'];
        echo json_encode((new OrderRepository())->getOrdersByStatus($status_id));
    }

    } catch (Exception $e) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 501 Internal Server Error', true, 500);
        echo json_encode(array("success" => false, "message" => $e->getMessage()));
        return;
    }
}
}
?>
