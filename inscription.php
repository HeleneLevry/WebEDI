<!-- inscription.php -->

<!--?php require "session.php" ?-->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>inscription.php</title>
</head>

<body>

    <h1>Sign in</h1>
    <hr>

    <!-- Error message in case of bad identifiers -->
    <?php
    if (isset($_GET['error']) AND $_GET['error']=="missingArg"){
        echo '<p class="err">Some fields are missing</p>';
    }
    else if (isset($_GET['error']) AND $_GET['error']=="loginExists"){
        echo '<p class="err">This login already exists, please change it</p>';
    }
    else if (isset($_GET['error']) AND $_GET['error']=="emailNotConfirm"){
        echo '<p class="err">The two email fields are differents</p>';
    }
    else if (isset($_GET['error']) AND $_GET['error']=="pwdNotConfirm"){
        echo '<p class="err">The two password fields are differents</p>';
    }
    else if (isset($_GET['error']) AND $_GET['error']=="passwordNotHashed"){
        echo '<p class="err">Error when hashing the password</p>';
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
                <!-- Name -->
                <p>Name</p>
                <!-- Firstname -->
                <p>Firstname</p>
                <!-- hr-->
                <hr class="hidden hrFine">
                <!-- Login -->
                <p>Login</p>
                <!-- p -->
                <hr class="hidden hrFine">
                <!-- Email -->
                <p>Email</p>
                <!-- Email Confirm -->
                <p>Confirm email</p>  
                <!-- hr-->
                <hr class="hidden hrFine">             
                <!-- Password -->
                <p>Password</p>
                <!-- Password Confirm -->
                <p>Confirm password</p>
                <!-- hr-->
                <hr class="hidden hrFine">
                
                <!-- Retour -->
                <p><input class="button" type="button" value="Log in" onclick="window.location.href='connection.php'"/></p>
            </div>

            <!-- Element 2 -->
            <div class="element">
                <!-- Name -->
                <p> <input type="text" required name="name" /> </p>
                <!-- Firstname -->
                <p> <input type="text" required name="firstname" /> </p>
                <!-- hr-->
                <hr class="hidden hrFine">
                <!-- Login -->
                <p> <input type="text" required name="login" /> </p>
                <!-- hr-->
                <hr class="hidden hrFine">
                <!-- Email -->
                <p> <input type="email" required name="email" /> </p>
                <!-- Email confirm-->
                <p> <input type="email" required name="emailConfirm" /> </p>
                <!-- hr-->
                <hr class="hidden hrFine">
                <!-- Password -->
                <p> <input type="password" required name="password" /> </p>
                <!-- Password confirm-->
                <p> <input type="password" required name="passwordConfirm" /> </p>
                <!-- hr-->
                <hr class="hidden hrFine">
                
                <!-- Validate -->
                <p> <input class="button" type="submit" value="Validate" /> </p>
            </div>

        </div>

    </form>

</body>

</html>


<!--Name
<p>
    <label for="name">Name</label>
    <input type="text" required name="name" id="name"/>
</p>
Firstname
<p>
    <label for="firstname">Firstname</label>
    <input type="text" required name="firstname" id="firstname" />
</p> -->