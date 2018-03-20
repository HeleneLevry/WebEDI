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

    <div class="colonneBody">

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
        }
        ?>

        <!-- Correct URL parameters: print the page -->
        <?php
        if($ValidParam){
        // Print user's identity and title
            echo ("<h1>" . $_SESSION['firstname'] . " " . strtoupper($_SESSION['name']) . "</h1>");
        // Subtitle
            echo("<hr>
                <h2>What transaction do you want to do?</h2>
                <hr>");
        // Explanation
            echo ("<p class='info'>You need to enter at least one transaction.<br>
                If some of the fields of a transaction are incomplete, the transaction will not be completed.</p>");
            ?>

            <!-- Form -->
            <form method="post" action="do.traitement.php">
                <div class="colonne">
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
                        <fieldset>
                            <legend>
                                <?php echo ("Transaction n°".($i+1)) ?>
                            </legend>
                            
                            <div>
                                <!-- Transaction type -->
                                <p>
                                    <label for=<?php echo $transactionType ?>>
                                        <span class="label"> Transaction type </span>
                                        <select name=<?php echo $transactionType ?>>
                                            <option value="" disable selected>Select the transaction type</option>
                                            <option value="credit">credit transfert</option>
                                            <option value="debit">direct debit</option>
                                        </select>
                                    </label>
                                </p>
                                <!-- Amount -->
                                <p>
                                    <label for=<?php echo $amount ?>>
                                        <span class="label"> Amount </span>
                                        <input type="number" step="0.01" name=<?php echo $amount ?> value=0.00 />
                                    </label>
                                </p>
                                <!-- Currency -->
                                <p>
                                    <label for=<?php echo $currency ?>>
                                        <span class="label"> Currency </span>
                                        <select name=<?php echo $currency ?>>
                                            <option value="" disable selected>Select the amount's currency</option>
                                            <option value="dollars">dollars ($)</option>
                                            <option value="euros">euros (€)</option>
                                            <option value="livres">pounds sterling (£)</option>
                                        </select>
                                    </label>
                                </p>
                                <!-- Origin account -->
                                <p>
                                    <label for=<?php echo $originAccount ?>>
                                        <span class="label"> Origin account </span>
                                        <input type="text" name=<?php echo $originAccount ?> />
                                    </label>
                                </p>
                                <!-- Destination account -->
                                <p>
                                    <label for=<?php echo $destinationAccount ?>>
                                        <span class="label"> Destination account </span>
                                        <input type="text" name=<?php echo $destinationAccount ?> />
                                    </label>
                                </p>
                            </div>
                            <!-- </div> -->
                        </fieldset>
                        <hr class="hrFine">
                        <?php } ?>

                        <!-- Validate -->
                        <p class="rightAlign bottomButton">
                            <input class="button" type="submit" value="Validate" />
                        </p>
                    
                    <?php } ?>
                </div>
            </form>
    </div>

</body>

</html>