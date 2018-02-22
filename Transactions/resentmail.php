<!-- resentmail.php -->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>Web-EDI - Activation mail</title>
</head>

<body>

    <!-- Title -->
    <h1>Send activation email</h1>
    <hr>

    <!-- Error messages -->
    <?php
    if (isset($_GET['error'])) {
        printError();
    }
    ?>

    <!-- Form -->
    <form method="post" action="do.resentmail.php">

        <!-- Conteneur -->
        <div id="conteneur">

            <!-- Element 1 -->
            <div class="element">
                <!-- Login -->
                <p>Login</p>
                <!-- email -->
                <p>Email</p>
            </div>

            <!-- Element 2 -->
            <div class="element">
                <!-- Login -->
                <p> <input type="text" required name="login" value="<?php valueCookie() ?>"/> </p>
                <!-- Email -->
                <p> <input type="email" required name="email" /> </p>
                <!-- Validate -->
                <p> 
                    <input class="button" type="submit" value="Send email" />
                </p>
            </div>

        <!-- End conteneur -->
        </div>
        
    <!-- End form -->
    </form>

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
            case 'missingArg':
                echo '<p class="err">Some fields are missing</p>';
                break;
            case 'mailNotSent':
                echo '<p class="err">Sorry, the confirmation email has not been sent. Please try again.</p>';
                break;
            case 'unknow':
                echo '<p class="err">Error unknown</p>';
                break;
            default: 
                echo '<p class="err">An error is detected but not identified</p>';
        }
    }
?>