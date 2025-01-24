<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reader Dashboard</title>
</head>
<body>
<?php

    use repository\CopyRepository;use repository\RentalRepository;
    require_once __DIR__ . '/../../src/repository/ReaderRepository.php';
    require_once __DIR__ . '/../../src/repository/RentalRepository.php';
    require_once __DIR__ . '/../../src/repository/CopyRepository.php';
    require_once __DIR__ . '/../../src/repository/BookRepository.php';

    $readerRepository = new ReaderRepository();
    $currentUser = $readerRepository->getById($_SESSION['Reader']);

    $rentalRepository = new RentalRepository();
    $rentals = $rentalRepository->getByReaderId($currentUser->getId());
    $copyRepository = new CopyRepository();
    $bookRepository = new BookRepository();
    ?>

<h1>Reader Dashboard</h1>

<div>
    <h2>Welcome, <?php echo $currentUser->getName(); ?>!</h2>

    <p>ID: <?php echo $currentUser->getId(); ?></p>
    <p>Imię: <?php echo $currentUser->getName(); ?></p>
    <p>Nazwisko: <?php echo $currentUser->getSurname(); ?></p>
    <p>Email: <?php echo $currentUser->getEmail(); ?></p>
    <p>Pesel: <?php echo $currentUser->getPesel(); ?></p>

    <form action="/logout">
        <input type="submit" value="Logout">
    </form>
    <button onclick="window.location.href='/'">Go Back to Main Page</button>

    <?php
        echo '<h2>Moje wypożyczenia</h2>';
        foreach ($rentals as $rental){
            $bookId = $copyRepository->getBookId($rental->getCopyId());
            $book =  $bookRepository->getById($bookId);
            $receivedStatus = $rental->isReceived() ? 'tak' : 'nie';
            echo '<p>'.$book->getTitle().' Wypożyczone od: '.$rental->getRentalDate().
                ' Regulaminowy termin zwrotu: '.$rental->getReturnTo().
                ' Data zwrotu: '.$rental->getReturnDate().
                ' Odebrane: '.$receivedStatus;
                '</p>';
        }
    ?>
</div>
</body>
</html>
