<!-- saisie.php -->

<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="base.css" />
    <title>saisie.php</title>
</head>

<body>

    <!-- URL parameters recovery -->
    <?php

    $paramValid=false;

    // Name
    if ((isset($_GET['name'])) AND (isset($_GET['firstname'])) AND (isset($_GET['id']))){
        // Name
        $name = $_GET['name'];
        // Firstname
        $firstname = $_GET['firstname'];
        // Id
        $id = $_GET['id'];
        // paramValid to true
        $paramValid=true;    
    }
    // Print the message of error if the parameter is set to true
    elseif (isset($_GET['error']) AND $_GET['error']==true){
        echo '<p class="err">Incorrect information</p>';
    }
    // Redirect to index.php with error message if wrong identity
    else{
        header("Location: saisie.php?error=true");
        exit();
    }?>

        <?php
    // paramValid
    if ($paramValid){
        // Print user's identity and title
        echo ("
            <h1>Hi " . strtoupper($name) . " " . $firstname . "</h1>
            <hr>
            <h2>What transaction do you want to do?</h2>
            ");?>

            <!-- Form -->
            <form method="post" action="do.traitement.php?id=<?php echo $id ?>">

                <!-- Conteneur -->
                <div id="conteneur">

                    <!-- Element 1 -->
                    <div class="element">
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

                        <!-- Transaction type -->
                        <p>
                            <select name="transactionType">
                                <option value="" disable selected>Select the transaction type</option>
                                <option value="credit">credit transfert</option>
                                <option value="debit">direct debit</option>
                            </select>
                        </p>
                        <!-- Amount -->
                        <p>
                            <input type="number" name="amount" />
                        </p>
                        <!-- Currency -->
                        <p>
                            <select name="currency">
                                <option value="" disable selected>Select the amount's currency</option>
                                <option value="dollars">dollars ($)</option>
                                <option value="euros">euros (€)</option>
                                <option value="livres">pounds sterling (£)</option>
                            </select>
                        </p>
                        <!-- Origin account -->
                        <p>
                            <input type="text" name="originAccount" />
                        </p>
                        <!-- Destination account -->
                        <p>
                            <input type="text" name="destinationAccount" />
                        </p>

                        <!-- Validate -->
                        <p class="rightAlign">
                            <input class="button" type="submit" value="Validate" /> </p>
                    </div>

                </div>

            </form>

            <?php
    }?>

</body>

</html>