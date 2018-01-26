<!-- resultat.php -->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>resultat.php</title>
</head>

<body>

    <!-- Global variables -->
    <?php
        $id = '';
        $date = '';
        $error = '';
    ?>

    <!-- URL parameters recovery -->
    <?php
    $paramValid=false;
    // Credit/Debit sum and count
    $CreditCount = 0;
    $DebitCount = 0;
    $CreditSum = 0;
    $DebitSum = 0;

    // Name
    if ((isset($_GET['id'])) AND (isset($_GET['date']))){
        // id
        $id = $_GET['id'];
        // date
        $date = $_GET['date'];
        // paramValid to true
        $paramValid=true;    
    }
    // Print the message of error if the parameter is set to true
    elseif (isset($_GET['error']) AND $_GET['error']=='missingArg'){
        echo '<p class="err">Incorrect information</p>';
    }
    // Redirect to resultat.php with error message if wrong identity
    else{
        header("Location: resultat.php?error=missingArg");
        exit();
    }?>


    <?php
    // paramValid
    if ($paramValid){
        ?>
        <h1>Operations</h1>
        <hr>

        <?php
        //--- Credit
        @ $fcredit = fopen("dataSaved/vir.txt", "r");
        // Error if file not found
        if (!$fcredit){
            echo ("ERROR : file vir.txt could not be opened");
            exit;
        }
        $endFileCre = false;
        // Lock the file - exclusive writing
        flock($fcredit, 1);
        while(!feof($fcredit) AND !$endFileCre){
            // Read the file lign by lign
            $ligne = fgets($fcredit);
            if ($ligne == ""){
                $endFileCre = true;
            }
            else{
                // Split by ; and save the values
                list($lotFile, $idFile, $amountFile, $currencyFile, $originAccountFile, $destinationAccountFile) = explode(";", $ligne);
                // Test timestamp
                if ($date===$lotFile){
                    // Test id
                    if ($id===$idFile){
                        $CreditCount += 1;
                        $CreditSum +=$amountFile;
                    }
                }
            }

        }
        // Unlock the file
        flock($fcredit, 3);
        // Close the file
        fclose($fcredit);

        //--- Debit
        @ $fdebit = fopen("dataSaved/pre.txt", "r");
        // Error if file not found
        if (!$fdebit){
            echo ("ERROR : file pre.txt could not be opened");
            exit;
        }
        $endFileDeb = false;
        // Lock the file - exclusive writing
        flock($fdebit, 1);
        while(!feof($fdebit) AND !$endFileDeb){
            // Read the file lign by lign
            $ligne = fgets($fdebit);
            if ($ligne == ""){
                $endFileDeb = true;
            }
            else{
                // Split by ; and save the values
                list($lotFile, $idFile, $amountFile, $currencyFile, $originAccountFile, $destinationAccountFile) = explode(";", $ligne);
                // Test timestamp
                if ($date===$lotFile){
                    // Test id
                    if ($id===$idFile){
                        $DebitCount += 1;
                        $DebitSum +=$amountFile;
                    }
                }
            }

        }
        // Unlock the file
        flock($fdebit, 3);
        // Close the file
        fclose($fdebit);
        ?>



        <!-- Conteneur -->
        <div id="conteneurR">

            <!-- Element 1 -->
            <div class="elementR">
                <p> <br> </p> 

                <p> <!-- Credit -->
                    <h3>Credit transfert</h3> </p>
                    
                    <p> <!-- Credit -->
                        <h3>Direct debit</h3> </p>

                    </div>

                    <!-- Element 2 -->
                    <div class="elementR">
                        <p> <h4> Number </h4> </p>

                        <p> <?php echo ($CreditCount)?> </p>

                        <p> <?php echo ($DebitCount)?> </p>

                    </div>

                    <!-- Element 3 -->
                    <div class="elementR">
                        <p> <h4> Amount </h4> </p>

                        <p> <?php echo ($CreditSum)?> </p>

                        <p> <?php echo ($DebitSum)?> </p>
                        
                    </div>

                </div>
                <?php
            }
            ?>

        </body>

        </html>