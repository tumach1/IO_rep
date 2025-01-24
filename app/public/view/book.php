<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/public/img/favicon.ico">
    <link rel="stylesheet" href="/public/css/globals.css" />
    <link rel="stylesheet" href="/public/css/book.css" />
</head>

<body>
    <div class="root-div">
        <div class="page-div">
            <div class="top-panel">
                <a href="../order_page/index.html">
                    <div class="top-orders-text">Orders</div>
                </a>
                <a href="/">
                    <div class="top-library-text">Library</div>
                </a>
<!--                <a href="../order_page/index.html">-->
<!--                    <div class="top-order-new-book">-->
<!--                        <div class="top-order-new-book-text">+ Order a New Book</div>-->
<!--                    </div>-->
<!--                </a>-->

                <div class="top-user-info">
                    <div class="top-user-info-icon"></div>
                    <a href="../dashboard">
                        <div class="top-user-info-text">
                            <?php
                            require_once __DIR__ . '/../../src/repository/ReaderRepository.php';
                            require_once __DIR__ . '/../../src/repository/WorkerRepository.php';

                            session_start();
                            if(isset($_SESSION['Reader']))
                            {
                                $readerRepository = new ReaderRepository();
                                $currentUser = $readerRepository->getById($_SESSION['Reader']);
                                echo $currentUser->getName();
                            } else
                            {
                                echo "Profile";
                            }
                            ?>
                        </div>
                    </a>
                    <img class="top-user-info-arrow" src="/public/img/arrow-down.svg" />
                </div>
            </div>

            <div class="info-panel">
                <span class="info-image">
                    <img src="../img/placeholder-portrait.png" alt="">
                </span>
                <span class="info-data">
                    <div class="author">
                        <a href=""><span class="text">Some Author</span></a>
                    </div>
                    <div class="title">
                        <span>Title of the Very Awesome Book</span>
                    </div>
                    <div class="subtitle">
                        <span>This is test subtitle for the book we are about to show you right now</span>
                    </div>
                    <div class="genres">
                        <span class="genre-bg"><span class="genre-text">Fiction</span></span>
                    </div>
                    <div class="pages frame">
                        <span class="label">Number of pages:</span>
                        <span class="text">976</span>
                    </div>
                    <div class="location frame">
                        <span class="label">Location:</span>
                        <span class="text">Krakow, Poland</span>
                    </div>
                    <div class="copies frame">
                        <span class="label">Number of copies:</span>
                        <span class="text">1,009,502</span>
                    </div>
                    <div class="original-title frame">
                        <span class="label">Original Title:</span>
                        <span class="text">Some Title of Awesome Book</span>
                    </div>
                    <div class="original-subtitle frame">
                        <span class="label">Original Subtitle:</span>
                        <span class="text">Some subtitle of this awesome book but in original language</span>
                    </div>
                    <div class="binding frame">
                        <span class="label">Binding:</span>
                        <span class="text">What is it</span>
                    </div>
                    <div class="ISBN frame">
                        <span class="label">ISBN:</span>
                        <span class="text">1234567898</span>
                    </div>
                </span>
            </div>
            <div class="button-panel">
                <a href="../orderPage?book_id=<?php echo $id; ?>" class="order-book-btn"><span>Order Book</span></a>
                <a href="../orderPage" class="book-book-btn"><span>Book from 20.09</span></a>
            </div>
            <div class="description-panel">
                <span class="description-text">Every day, more and more people want to learn some HTML and CSS. Joining the professional web designers and programmers are new audiences who need to know a little bit of code at work (update a content management system or e-commerce store) and those who want to make their personal blogs more attractive. Many books teaching HTML and CSS are dry and only written for those who want to become programmers, which is why this book takes an entirely new approach.</span>
            </div>
        </div>
    </div>
    </div>

    <script>
        <?php
            if(isset($id)) {
                echo 'let idBook = ' . $id . ';';
            }
        ?>
    </script>
    <script src="/public/js/book.js"></script>
</body>

</html>