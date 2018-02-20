<!-- connection.php -->

<!--?php require "session.php" ?-->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>connection.php</title>
</head>

<body>

    <h1>Log in</h1>
    <hr>

    <!-- Error message in case of bad identifiers -->
    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error']=='missingArg') {
            echo '<p class="err">Some fields are missing</p>';
        }
        else if ($_GET['error']=='accountActivation') {
            echo '<p class="err">Your account has been activated</p>';
        }
        else if ($_GET['error']=='errConnectionDB') {
            echo '<p class="err">Cannot connect to the database</p>';
        }
        else if ($_GET['error']=='NotActive') {
            echo '<p class="err">Your account isn\'t active yet</p>';
        }
        else if ($_GET['error']=='TooManyAttemps') {
            echo '<p class="err">You tried to connect too many times</p>';
        }
        else if ($_GET['error']=='loginNotFound') {
            echo '<p class="err">This login is not in the database</p>';
        }
        else if ($_GET['error']=='pwdWrong') {
            echo '<p class="err">Wrong password</p>';
        }
        else if ($_GET['error']=='sessionExpired') {
            echo '<p class="err">Your session expired. Please reconnect.</p>';
        }
        else if ($_GET['error']=='deconnect') {
            echo '<p class="err">Deconnection successful.</p>';
        }
        else if ($_GET['error']=='unknow') {
            echo '<p class="err">Error unknown</p>';
        }
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
                <p><input class="button" type="button" value="Sign in" onclick="window.location.href='inscription.php'"></p>
            </div>

            <!-- Element 2 -->
            <div class="element">
                <!-- Login -->
                <p> <input type="text" required name="login" /> </p>
                <!-- Password -->
                <p> <input type="password" required name="password" /> </p>
                <!-- Validate -->
                <p class="rightAlign"> <input class="button" type="submit" value="Validate" /> </p>
            </div>

        </div>

    </form>

</body>

</html>