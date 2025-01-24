<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/globals.css" />
    <link rel="stylesheet" href="/public/css/home.css" />

  </head>
  <body>
    <?php
    session_start();
    ?>
    
    <div class="ipad-pro">
      <div class="div">
      
        
        <div class="top-panel">
          <a href="./myorders">
              <div class="top-orders-text">Orders</div>
          </a>
          <a href="/">
              <div class="top-library-text">Oferta</div>
          </a>
<!--          <a href="/orderPage">-->
<!--              <div class="top-order-new-book">-->
<!--                  <div class="top-order-new-book-text">+ Order a New Book</div>-->
<!--              </div>-->
<!--          </a>-->
          <!-- Koszyk (Cart) -->
          
          <div id="cart" class="cart">
          <div class="header">
            <h2>Your Cart</h2><span class="cart-close" onclick="toggleCart()"><img width="20px" src="/public/img/cross.png" alt="x"></span>
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
                      require_once __DIR__ . '/../../src/repository/ReaderRepository.php';
                      require_once __DIR__ . '/../../src/repository/WorkerRepository.php';
                      if (isset($_SESSION['Reader']))
                      {
                          $readerRepository = new ReaderRepository();
                          $currentUser = $readerRepository->getById($_SESSION['Reader']);
                          echo $currentUser->getName();
                      } elseif (isset($_SESSION['Worker']))
                      {
                          $workerRepository = new WorkerRepository();
                          $currentUser = $workerRepository->getById($_SESSION['Worker']);
                          echo $currentUser->getName();
                      } else
                      {
                          echo "Profile";
                      }
                      ?>
                  </div>
              </a>
              <!-- <img class="top-user-info-arrow" src="/public/img/arrow-down.svg" /> -->
          </div>
      </div>
      
      
        
        <div class="search-category">
          <div class="search-input">
            <div class="search-sign">
              <div class="overlap-group-2">
                <div class="ellipse"></div>
                <img class="line-4" src="/public/img/line-1.svg" />
              </div>
            </div>
            <input type="text" placeholder="Search for manga" class="filler-text">
            <img src="/public/img/cross.png" alt="cross" class="search-cross-btn">
          </div>
        
        
          <div class="category-dropdown-content">
            <div class="genre-frame">
              <div class="genre-text">Fiction</div>
            </div>
          </div>
        
          <div class="category-dropdown">
            <div class="filler-text-2">Category</div>
            <div class="arrow">
              <div class="div-2">
                <img class="line-5" src="/public/img/line-2.svg" /> <img class="line-6" src="/public/img/line-3.svg" />
              </div>
            </div>
          </div>
        </div>

        <div class="tabs-news">        
          <div class="book-tabs">
            <div class="overlap">
              <div class="overlap-group">
                <div class="line-2"></div>
        
                <div class="hor-scrl-tabs">
                  <div class="hor-scrl-tab" id="tab-recommended">
                    <div class="tab-name">Recommended</div>
                    <div class="underline"></div>
                  </div>
                  <!-- <div class="hor-scrl-tab" id="tab-recent">
                    <div class="tab-name">Recently Read</div>
                    <div class="underline"></div>
                  </div> -->
                </div>
        
        
              </div>
        
              <div class="hor-scrl-arrows">
                <img src="/public/img/arrow-left.png" id="hor-scrl-arrow-left">
        
                <div id="btn-0">
                  <div id="btn-0-bg"></div>
                  <div id="btn-0-filler"></div>
                </div>
        
                <div id="btn-1">
                  <div id="btn-1-bg"></div>
                  <div id="btn-1-filler"></div>
                </div>
        
                <div id="btn-2">
                  <div id="btn-2-bg"></div>
                  <div id="btn-2-filler"></div>
                </div>
                <img src="/public/img/arrow-right.png" id="hor-scrl-arrow-right">
              </div>
            </div>
        
        
        
        
            <div class="hor-scrl-pages">
              <div class="hor-scrl-content">
        
                <span class="hor-scrl-page">
                  <a href = "http://google.com">
                  <div class="img-container">
                    <img src="/public/img/placeholder-portrait.png">
                  </div>
                </a>
                  <div class="footer">
                    <div class="genre-frame">
                      <div class="genre-text">Fiction</div>
                    </div>
                    <div class="description-frame">
                      <p class="description-text">
                        This book is about young mage that was travelling with his friends around the world when suddenly
                        happened This book is about young mage that was travelling with his friends around the world when
                        suddenly
                      </p>
                    </div>
                    <div class="name-frame">
                      <a href=""><div class="name-text">Manga</div></a>
                    </div>
                  </div>
                </span>
        
              </div>
            </div>
        
        
        
          </div>
          
          <div class="news">
            <div class="line-3"></div>
            <div class="news-text">Events</div>
          
            <a href="https://www.instagram.com/magnificon_expo/?hl=ru">
              <div class="news-image"></div>
            </a>
          </div>
          </div>

        <div class="book-list-panel">

          <div class="book-list-title">
            <div class="bot-scrl-div-name"><div class="text-wrapper-3">Title</div></div>
            <div class="bot-scrl-div-author"><div class="text-wrapper-3">Author</div></div>
            <div class="bot-scrl-div-genre"><div class="text-wrapper-3">Category</div></div>
            <div class="bot-scrl-div-opportunity"><div class="text-wrapper-3">Opportunities</div></div>
            <div class="bot-scrl-div-status"><div class="text-wrapper-3">Status</div></div>
            <div class="bot-scrl-div-action"><div class="text-wrapper-3">Action</div></div>
          </div>

          <div class="book-list">
            <div class="book-card-horizontal">
              <div class="line"></div>
              <div class="bot-scrl-div-name">
                <div class="image"><img class="img bot-scrl-img" src="/public/img/placeholder-portrait.png" /></div>

             
                <a href = "http://google.com">
                <span class="bot-scrl-name">Good Book 1</span>
                </a>
              </div>
              
              <div class="bot-scrl-div-author">                
                <a href = "http://google.com"><div class="text-wrapper bot-scrl-author">Good Author 1</div></a>
              </div>

              <div class="bot-scrl-div-genre">
                <div class="genre-frame"><div class="genre-text bot-scrl-genre">Fiction</div></div>
              </div>
              <div class="bot-scrl-div-opportunity"><div class="text-wrapper bot-scrl-opportunity">can buy</div></div>
              <div class="bot-scrl-div-status"><div class="text-wrapper bot-scrl-status">Booked</div></div>
              <div class="bot-scrl-action">
              
              <div class="action" data-id="1" onclick="addToCart(this.getAttribute('data-id'));">
            <div class="action-2 bot-scrl-action .add-to-cart-btn">Dodaj do koszyka</div>
        </div>
                
              </div>
            </div>


            <div class="line-end"></div>



          </div>
        </div>






      </div>
    </div>

    
    <script src="/public/js/home.js"></script>
  </body>
</html>
