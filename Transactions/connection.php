<!-- connection.php -->

<!--?php require "session.php" ?-->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>Web-EDI - Log in</title>
</head>

<body>

    <div class="colonneBody">

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

        <div class="colonne">

            <!-- Login -->
            <p>
                <label for="login">
                    <span class="label"> Login </span>
                    <input type="text" required name="login" value="<?php valueCookie() ?>"/>
                </label>
            </p>
            <!-- Password -->
            <p>
                <label for="password">
                    <span class="label"> Password </span>
                    <input type="password" required name="password" />
                </label>
            </p>
            <p class="center">
                <span class="leftAlign"> <input class="button" type="button" value="Sign in" onclick="window.location.href='inscription.php'"> </span> 
                <span class="rightAlign"> <input class="button" type="submit" value="Validate" /> </span>
            </p>

        </div>

    </form>
</div>

</body>

</html>

<!-- -------------------- FUNCTIONS -------------------- -->

<!-- Fill with value cookie -->
<?php
    function valueCookie(){
        if (isset($_COOKIE["WebEDI_login"])) {
            echo $_COOKIE["WebEDI_login"];
        }
    }
?>

<!-- Error messages function -->
<?php
    function printError(){
        switch($_GET['error']) {
            case 'sessionExpired':
                echo '<p class="err">Your session is expired, please reconnect</p>';
                break;
            case 'deconnect':
                echo '<p class="err">Deconnection successful</p>';
                break;
            case 'accountActivation':
                echo '<p class="err">Your account has been activated successfully</p>';
                break;
            case 'missingArg':
                echo '<p class="err">Some fields are missing</p>';
                break;
            case 'errConnectionDB':
                echo '<p class="err">Cannot connect to the database</p>';
                break;
            case 'loginNotFound':
                echo '<p class="err">Sorry, there is no account for this login</br>You should sign in</p>';
                break;
            case 'NotActive':
                echo '<p class="err">
                    Sorry, your account isn\'t active yet
                    </br>
                    Please check your mails and click on the link provided
                    </br>
                    You can also go to <a href="do.resentmail.php"> 
                    this page </a> to resend the activation mail
                    </p>';
                break;
            case 'TooManyAttemps':
                echo '<p class="err">Sorry, you\'ve tried to connect too many times</p>';
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