<!-- inscription.php -->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>Web-EDI - Sign In</title>
</head>

<body>

    <!-- Title -->
    <h1>Sign in</h1>
    <hr>

    <!-- Error messages -->
    <?php
    if (isset($_GET['error'])) {
        printError();
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
                <p> <input type="text" required name="login" </p>
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

<!-- -------------------- FUNCTIONS -------------------- -->

<!-- Error messages function -->
<?php
    function printError(){
        switch($_GET['error']) {
            case 'missingArg':
                echo '<p class="err">Some fields are missing</p>';
                break;
             case 'errConnectionDB':
                echo '<p class="err">Cannot connect to the database</p>';
                break;
            case 'loginExists':
                echo '<p class="err">Sorry, this login already exists, please choose another one</p>';
                break;
            case 'emailNotConfirm':
                echo '<p class="err">Sorry, the two email fields are differents</p>';
                break;
            case 'pwdNotConfirm':
                echo '<p class="err">Sorry, the two password fields are differents</p>';
                break;
            case 'incorrectPassword':
                echo '<p class="err">Sorry, the password is not correct</br>Password must be 8 charaters long and contain lowercase, uppercase and number</p>';
                break;
            case 'passwordNotHashed':
                echo '<p class="err">Sorry, we encountered an issue when hashing the password</p>';
                break;
            case 'mailNotSent':
                echo '<p class="err">Sorry, the confirmation email has not been sent. Please go to <a href="http://localhost/web-edi/do.resentmail.php"> 
                    this page </a></p>';
                break;
            case 'unknow':
                echo '<p class="err">Error unknown</p>';
                break;
            default: 
                echo '<p class="err">An error is detected but not identified</p>';
        }
    }
?>


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