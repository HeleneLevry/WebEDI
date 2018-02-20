<!-- connection.php -->

<!--?php require "session.php" ?-->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>Web-EDI - Connection</title>
</head>

<body>

    <!-- Title -->
    <h1>Log in</h1>
    <hr>

    <!-- Error messages -->
    <?php
    if (isset($_GET['error'])) {
        printError();
    }
    ?>

    <!-- Form -->
    <form method="post" action="do.autentification.php">

        <!-- Conteneur -->
        <div id="conteneur">

            <!-- Element 1 -->
            <div class="element">
                <!-- Login -->
                <p>Login</p>
                <!-- Password -->
                <p>Password</p>
                <!-- Retour -->
                <p>
                    <input class="button" type="button" value="Sign in" onclick="window.location.href='inscription.php'">
                </p>
            </div>

            <!-- Element 2 -->
            <div class="element">
                <!-- Login -->
                <p> <input type="text" required name="login" /> </p>
                <!-- Password -->
                <p> <input type="password" required name="password" /> </p>
                <!-- Validate -->
                <p> 
                    <input class="button" type="submit" value="Validate" />
                </p>
            </div>

        <!-- End conteneur -->
        </div>
        
    <!-- End form -->
    </form>

</body>

</html>

<!-- Error messages function -->
<?php
    function printError(){
        switch($_GET['error']) {
            case 'missingArg':
                echo '<p class="err">Some fields are missing</p>';
                break;
            case 'sessionExpired':
                echo '<p class="err">Your session is expired, please reconnect</p>';
                break;
            case 'deconnect':
                echo '<p class="err">Deconnection successful</p>';
                break;
            case 'accountActivation':
                echo '<p class="err">Your account has been activated successfully</p>';
                break;
            case 'NotActive':
                echo '<p class="err">Sorry, your account isn\'t active yet</p>';
                break;
            case 'errConnectionDB':
                echo '<p class="err">Cannot connect to the database</p>';
                break;
            case 'TooManyAttemps':
                echo '<p class="err">Sorry, you\'ve tried to connect too many times</p>';
                break;
            case 'loginNotFound':
                echo '<p class="err">Sorry, there is no account for this login</p>';
                break;
            case 'pwdWrong':
                echo '<p class="err">Sorry, this is a wrong password</p>';
                break;
            case 'unknow':
                echo '<p class="err">Error unknown</p>';
                break;
            default: 
                echo '<p class="err">An error is detected but not identified</p>';
        }
    }
?>