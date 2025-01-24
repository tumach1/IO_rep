<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders Admin</title>
    <link rel="stylesheet" type="text/css" href="/public/css/ordersAdmin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/globals.css" />
    <script src="/public/js/ordersAdmin.js" defer></script>
</head>
<body>
    <div id="user-id" style="display: none;">
        <?php 
        session_start();
        if (isset($_SESSION['Reader'])) {
            echo $_SESSION['Reader'];
        } elseif (isset($_SESSION['Worker'])) {
            echo $_SESSION['Worker'];
        } else {
            echo "0";
        
        }
        ?>
        </div>
    <div class="ipad-pro">
        <div class="div">
            <div class="top-panel">
                <a href="/ordersAdmin">
                    <div class="top-library-text">Orders</div>
                </a>
                <a href="/">
                    <div class="top-library-text">Oferta</div>
                </a>
                <div id="cart" class="cart">
                    <div class="header">
                        <h2>Your Cart</h2>
                        <span class="cart-close" onclick="toggleCart()"><img width="20px" src="/public/img/cross.png" alt="x"></span>
                    </div>
                    <ul id="cart-items"></ul>
                    <div class="action">
                        <div class="cart-checkout action-cart" onclick="createOrder(<?php echo $_SESSION['Reader']?>)">Checkout</div>
                    </div>
                </div>
                <div class="top-user-info">
                    <div class="top-cart" onclick="toggleCart()">
                        <img class="top-cart-icon" width="35px" src="/public/img/cart.png" />
                        <span class="top-cart-count" id="counter">0</span>
                    </div>
                    <div class="top-user-info-icon"></div>
                    <a href="./dashboard">
                        <div class="top-user-info-text">
                            <?php
                                // session_start();
                                require_once __DIR__ . '/../../src/repository/ReaderRepository.php';
                                require_once __DIR__ . '/../../src/repository/WorkerRepository.php';
                                if (isset($_SESSION['Reader'])) {
                                    //redirect to main page
                                    header("Location: /");
                                    
                                } elseif (isset($_SESSION['Worker'])) {
                                    $workerRepository = new WorkerRepository();
                                    $currentUser = $workerRepository->getById($_SESSION['Worker']);
                                    echo $currentUser->getName();
                                } else {
                                    header("Location: /");
                                }
                                ?>
                        </div>
                    </a>
                </div>
            </div>
            <div class="orders">
                <div class="orders-header">
                    <h1 class="orders-header">Orders</h1>
                </div>
                <div class="orders-list" id="orders-list">

                    <div class="orders-list-header">Order<div class="received-order-button">Received</div>
                    </div>

                    <div class="order-book-line">
                        <div class="image"><img class="img bot-scrl-img" src="/public/img/placeholder-portrait.png" />
                    </div>
                    <div class="order-book-name">
                        <a href="http://google.com">
                            <span class="bot-scrl-name">Good Book 1</span>
                        </a>
                    </div>
                    <div class="order-book-author">
                        <a href="http://google.com">
                            <div class="text-wrapper bot-scrl-author">Good Author 1</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
                    