<?php
require_once 'SessionController.php';
require_once __DIR__.'/../repository/OrdersRepository.php';

class OrderAPIController extends SessionController
{
    public function call(){

    try{
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = json_decode(file_get_contents('php://input'), true);
        $readerId = $data['user_id'];
        $bookIds = $data['bookIds'];

        (new OrderRepository())->addOrder($readerId, $bookIds);
    }
    else if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $readerId = $_GET['user_id'];
        echo json_encode((new OrderRepository())->getOrders($readerId));
    }
    else if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
        $data = json_decode(file_get_contents('php://input'), true);
        $orderId = $data['orderId'];
        (new OrderRepository())->deleteOrder($orderId);}
    else if($_SERVER['REQUEST_METHOD'] == 'PUT'){
        $data = json_decode(file_get_contents('php://input'), true);
        $orderId = $data['orderId'];
        $statusId = $data['statusId'];
        (new OrderRepository())->updateOrderStatus($orderId, $statusId);
    } }catch (Exception $e) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        echo json_encode(array("success" => false, "message" => $e->getMessage()));
        return;
    }
}
}
?>
