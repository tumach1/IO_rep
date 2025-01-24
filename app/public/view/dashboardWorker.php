<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/dashboardWorker.css" />
    <title>Worker Dashboard</title>
</head>
<body>
<?php

use repository\CopyRepository;use repository\RentalRepository;
require_once __DIR__ . '/../../src/repository/WorkerRepository.php';
require_once __DIR__ . '/../../src/repository/BookRepository.php';

$workerRepository = new WorkerRepository();
$currentUser = $workerRepository->getById($_SESSION['Worker']);


?>

<h1>Profil uzytkownika</h1>

<div>
    <h2>Welcome, <?php echo $currentUser->getName(); ?>!</h2>

    <div class="worker-profile">
        <img src="public/img/profile-circle.svg" alt="" width="200px">
        <p><?php echo $currentUser->getName(); ?> <?php echo $currentUser->getSurname(); ?></p>
        <p><?php echo $currentUser->getEmail(); ?></p>
        <p><?php echo $currentUser->getPesel(); ?></p>
        <p>Phone number: <?php echo $currentUser->getPhoneNumber(); ?></p>
    </div>

    <div class="actions">
        <div class="logout-button btn">
            <a href="/logout">Logout</a>
        </div>
        <div class="back btn">
            <a href="/">Main</a>
        </div>
        <div class="orders btn">
            <a href="/ordersAdmin">Orders</a>
        </div>
        <div class="returns btn">
            <a href="/returns">Returns</a>
        </div>
    </div>
</div>
</body>
</html>
