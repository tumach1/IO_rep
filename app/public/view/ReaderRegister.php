<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/x-icon" href="public/img/favicon.ico">
        <title>Manga</title>
        <link rel="stylesheet" href = "public/css/register.css" type="text/css">
        <script type="text/javascript" src="./public/js/script.js" defer></script>
    </head>
    <body>
        <div id="container_pc">
            <div id="banner">
                <h1><a id="main_page_link" href="/">Manga</a></h1>
                <img src="/public/img/logo.svg" alt="logo">
            </div>
            <div id = "main_content">
                <div id="messages">
                    <?php
                    if(isset($messages)){
                        foreach ($messages as $message){
                            echo $message;
                        }
                    }
                    ?>
                </div>

                <form action="" method="POST">
                    <input type="text" name="imie" id="imie" placeholder="imię" required>
                    <input type="text" name="nazwisko" id="nazwisko" placeholder="nazwisko" required>
                    <input type="text" name="pesel" id="pesel" placeholder="pesel" required>
                    <input type="email" name="email" id="email" placeholder="email" required>
                    <input type="text" name="ulica" id="ulica" placeholder="ulica" required>
                    <select name="miejscowosc" required>
                        <?php
                            require_once __DIR__.'/../../src/repository/CitiesRepository.php';
                            $repo = new CitiesRepository();
                            foreach ($repo->getCities() as $city){
                                echo '<option value="'.$city->getId().'">'.$city->getName().'</option>';
                            }
                        ?>
                    </select>
                    <input type="text" name="kod_pocztowy" id="kod_pocztowy" placeholder="kod-pocztowy" required>
                    <input type="password" name="password" id="password" placeholder="hasło" required>
                    <input type="password" name="confirmedPassword" id="confirmedPassword" placeholder="powtórz hasło" required>
                    <label>
                        <input type="checkbox" id="agreementCheckbox" name="agreementCheckbox" value="Akceptuję postanowienia regulaminu" required>
                        Akceptuję postanowienia regulaminu
                    </label>
                    <input class="userInput" id="registerButton" type="submit" value="Załóż konto">
                </form>
            </div>
            <div id="footer">
                <p>Contact details: illiashenkoalex@gmail.com</p>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var form = document.querySelector('form');

                form.addEventListener('submit', function (event) {
                    var password = document.getElementById('password').value;
                    var confirmedPassword = document.getElementById('confirmedPassword').value;

                    if (password.length < 8) {
                        alert('Password must be at least 8 characters long.');
                        event.preventDefault(); // Prevent form submission
                    } else if (password !== confirmedPassword) {
                        alert('Passwords do not match.');
                        event.preventDefault(); // Prevent form submission
                    }
                });
            });
        </script>
    </body>
</html>