<!-- saisie.php -->

<?php require "session.php" ?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>saisie.php</title>
</head>

<body>

    <p class="link rightAlign">
        <a href="do.deconnexion.php"> Deconnection </a>
    </p>

    <!-- URL parameters recovery -->
    <?php

    // Session expired
    if($_SESSION['last_activity'] < time()-$expire_time){
        header("Location: connection.php?error=sessionExpired");
        exit();
    }
    $_SESSION['last_activity'] = time();

    // Nb transaction
    $nbTransaction = 5;
    // Error
    $Error = '';
    $fromSaisie = false;
    $ValidParam = false;
    
    // Error messages
    if (isset($_GET['error'])) {
        if ($_GET['error']=='missingArg') {
            echo '<p class="err">Some fields are missing</p>';
        }
        else if ($_GET['error']=='missingArgFromSaisie') {
            echo '<p class="err">Some fields are missing from saisie.php</p>';
            $fromSaisie = true;
        }
         else if ($_GET['error']=='transactionFailed') {
            echo '<p class="err">Database connection error, transaction failed</p>';
            $fromSaisie = true;
        }
    }

    // parameters recovery
    if ((isset($_SESSION['name'])) AND (isset($_SESSION['firstname'])) AND (isset($_SESSION['userId']))){
        $ValidParam = true;
    }
    else {
        header("Location: connection.php?error=sessionExpired");
        exit();
        /*<p>
            You need to reconnect : <br>
            <input class="button" type="button" value="Log in" onclick="window.location.href='connection.php'"/>
        </p>   */  
    }
    ?>

    <!-- Correct URL parameters: print the page -->
    <?php
    if($ValidParam){
        // Print user's identity and title
    echo ("<h1>" . $_SESSION['firstname'] . " " . strtoupper($_SESSION['name']) . "</h1>");
    // Subtitle
    echo("<hr>
        <h2>What transaction do you want to do?</h2>");
    // Explanation
    echo ("<hr> <p class='info'>You need to enter at least one transaction.<br>
        If some of the fields of a transaction are incomplete, the transaction will not be completed.</p>");
    ?>


    <!-- Form -->
    <form method="post" action="do.traitement.php">
        <hr class="hrFine">
        <?php
        for($i=0;$i<$nbTransaction;$i++){
                // Form variables
            $transactionType=("transactionType".$i);
            $amount=("amount".$i);
            $currency=("currency".$i);
            $originAccount=("originAccount".$i);
            $destinationAccount=("destinationAccount".$i);
            ?>
            <!-- Conteneur -->
            <div id="conteneur">
                <!-- Element 1 -->
                <div class="element">
                    <!-- Title -->
                    <h4> <?php echo ("Tansaction n°".($i+1)) ?></h4>
                    <!-- Transaction type -->
                    <p>Transaction type</p>
                    <!-- Amount -->
                    <p>Amount</p>
                    <!-- Currency -->
                    <p>Currency</p>
                    <!-- Origin account -->
                    <p>Origin account</p>
                    <!-- Destination account -->
                    <p>Destination account</p>
                </div>
                <!-- Element 2 -->
                <div class="element">
                    <!-- Title -->
                    <h4><br></h4>
                    <!-- Transaction type -->
                    <p>
                        <select name=<?php echo $transactionType ?>>
                            <option value="" disable selected>Select the transaction type</option>
                            <option value="credit">credit transfert</option>
                            <option value="debit">direct debit</option>
                        </select>
                    </p>
                    <!-- Amount -->
                    <p>
                        <input type="number" step="0.01" name=<?php echo $amount ?> />
                    </p>
                    <!-- Currency -->
                    <p>
                        <select name=<?php echo $currency ?>>
                            <option value="" disable selected>Select the amount's currency</option>
                            <option value="dollars">dollars ($)</option>
                            <option value="euros">euros (€)</option>
                            <option value="livres">pounds sterling (£)</option>
                        </select>
                    </p>
                    <!-- Origin account -->
                    <p>
                        <input type="text" name=<?php echo $originAccount ?> />
                    </p>
                    <!-- Destination account -->
                    <p>
                        <input type="text" name=<?php echo $destinationAccount ?> />
                    </p>
                </div>
            </div>
            <hr class="hrFine">
            <?php
        }?>
        <!-- Conteneur -->
        <div id="conteneurValid">
            <!-- Element 1 -->
            <div class="element">
                <!-- Validate -->
                <p class="rightAlign">
                    <input class="button" type="submit" value="Validate" />
                </p>
            </div>
        </div>
    </form>


<?php
    }?>
    

</body>

</html>