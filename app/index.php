<?php
require "router.php";

date_default_timezone_set('Europe/Warsaw');

Router::get('/', 'HomeController');
Router::post('/books', 'BooksAPIController');
Router::get('/orders', 'OrdersController');


Router::get('/login', 'LoginController');
Router::post('/login', 'LoginFormController');

Router::get('/ReaderRegister', 'RegisterController');
Router::post('/ReaderRegister', 'RegisterFormController');

Router::get('/ResetPassword', 'ResetPasswordController');
Router::post('/ResetPassword', 'ResetPasswordController');

Router::get('/NewPassword', 'NewPasswordController');
Router::post('/NewPassword', 'NewPasswordController');

Router::get('/book/$id', 'BookController');
Router::post('/book/$id', 'BookAPIController');


Router::get('/dashboard', 'DashboardController');
Router::get('/logout', 'LogoutController');

Router::post('/order', 'OrderAPIController');
Router::get('/order', 'OrderAPIController');
Router::put('/order', 'OrderAPIController');


Router::get('/myorders', 'OrdersController');
Router::get('/ordersAdmin', 'OrderAdminController');


Router::get('/ordersByStatus', 'OrdersAdminApiController');

Router::get('/returns', 'ReturnsController');
