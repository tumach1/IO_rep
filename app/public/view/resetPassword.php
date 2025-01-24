<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/public/img/favicon.ico">
    <title>Biblioteka</title>
    <link rel="stylesheet" href = "/public/css/login.css" type="text/css">
</head>
<body>
<div id="container">
    <div id="banner">
        <h1><a id="main_page_link" href="/">Manga</a></h1>
        <img src="/public/img/logo.svg" alt="logo">
    </div>
    <div id="main_content">
        <div id="messages">
            <?php
                if(isset($messages)){
                    foreach ($messages as $message){
                        echo $message;
                    }
                }
                ?>
        </div>
        <form action="" method="POST" class="flex-column-center-center">
            <input class="userButton" type="text" name="email" placeholder="email" required> <br>
            <input id="loginButton" type="submit" value="Zresetuj hasło">
        </form>
    </div>
    <div id="">
        <p>Contact details: illiashenkoalex@gmail.com</p>
    </div>
</div>
</body>
</html>