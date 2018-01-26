<!-- inscription.php -->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>inscription.php</title>
</head>

<body>

    <h1>Inscription</h1>
    <hr>

    <!-- Error message in case of bad identifiers -->
    <?php
    if (isset($_GET['error']) AND $_GET['error']=="missingArg"){
        echo '<p class="err">Some fields are missing</p>';
    }
    else if (isset($_GET['error']) AND $_GET['error']=="loginExists"){
        echo '<p class="err">This login already exists, please change</p>';
    }
    else if (isset($_GET['error']) AND $_GET['error']=="pwdNotConfirm"){
        echo '<p class="err">The two password fields are differents</p>';
    }
    else if (isset($_GET['error']) AND $_GET['error']=="incorrectPassword"){
        echo '<p class="err">Password not correct. Password must be 8 charaters long and contain lowercase, uppercase and number.</p>';
    }
    ?>

    <!-- Form -->
    <form method="post" action="do.inscription.php">

        <!-- Conteneur -->
        <div id="conteneur">

            <!-- Element 1 -->
            <div class="element">
                <!-- Login -->
                <p>Login</p>
                <!-- Password -->
                <p>Password</p>
                <!-- Password Confirm -->
                <p>Confirm password</p>
                <!-- Name -->
                <p>Name</p>
                <!-- Firstname -->
                <p>Firstname</p>
                <!-- Retour -->
                <p><input class="button" type="button" value="Connection" onclick="window.location.href='connection.php'"></p>
            </div>

            <!-- Element 2 -->
            <div class="element">
                <!-- Login -->
                <p> <input type="text" required name="login" /> </p>
                <!-- Password -->
                <p> <input type="password" required name="password" /> </p>
                <!-- Password confirm-->
                <p> <input type="password" required name="passwordConfirm" /> </p>
                <!-- Name -->
                <p> <input type="text" required name="name" /> </p>
                <!-- Firstname -->
                <p> <input type="text" required name="firstname" /> </p>
                <!-- Validate -->
                <p class="rightAlign"> <input class="button" type="submit" value="Validate" /> </p>
            </div>

        </div>

    </form>

</body>

</html>