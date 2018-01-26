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
        // Parameters
    $id = '';
    $date = '';
    $error = '';
        // Credit/Debit sum and count
    $CreditCount = 0;
    $DebitCount = 0;
    $CreditSum = 0;
    $DebitSum = 0;
    ?>

    <h1>Operations completed</h1>
    <hr>

    <?php
        // Parameter control
    if ((isset($_GET['id'])) AND (isset($_GET['date']))){

            // Form values recovery
        $id = $_GET['id'];
        $date = $_GET['date'];

            // Database connection
        try{
            $dbhost = 'mysql:host=localhost;dbname=webedi';
            $dbuser = 'root';
            $dbmdp = '';
            $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $connection = new PDO( $dbhost, $dbuser, $dbmdp, $options);

                // Operation search
            $gabSearchOperation = 
            "SELECT count(*), sum(amount) 
            FROM operations 
            WHERE batch = ? AND type LIKE ? AND userId = ?";
            $prepSearchOperation = $connection->prepare($gabSearchOperation);

                // Credit Search
            $exeSearchCredit = $prepSearchOperation->execute(array($date, 'credit', $id));
            $resultatCredit = $prepSearchOperation->fetch(PDO::FETCH_NUM);
            $CreditCount = $resultatCredit[0];
            $CreditSum = $resultatCredit[1];

                // Debit Search
            $exeSearchDebit = $prepSearchOperation->execute(array($date, 'debit', $id));
            $resultatDebit = $prepSearchOperation->fetch(PDO::FETCH_NUM);
            $DebitCount = $resultatDebit[0];
            $DebitSum = $resultatDebit[1];

                /*echo($date.", 'credit', ".$id."<br>");
                echo("number of credit: " . $CreditCount . "<br> sum of credit: " . $CreditSum);
                echo($date.", 'debit', ".$id."<br>");
                echo("number of debit: " . $DebitCount . "<br> sum of debit: " . $DebitSum);
                exit();*/

                // Database connection
            } catch (Exception $e){
                echo"ERROR : MySQL connection failed : ", $e->getMessage();
                $RedirectSaisie = false;
                $Error = 'errConnectionDB';
                die();
            }

        // Parameter control    
        }
        // Print the message of error if the error is in argument
        elseif (isset($_GET['error']) AND $_GET['error']=='missingArg'){
            echo '<p class="err">Incorrect informations from Saisie.php</p>';
            exit();
        }
        // Redirect to resultat.php with error message if wrong identity
        else{
            header("Location: resultat.php?error=missingArg");
            exit();
        }
        ?>


        <!-- Conteneur -->
        <div id="conteneurR">

            <!-- Element 1 -->
            <div class="elementR">
                <p> <br> </p> 

                <p> <!-- Credit -->
                    <h3>Credit transfert</h3> </p>
                    
                <p> <!-- Debit -->
                    <h3>Direct debit</h3> </p>

            </div>

            <!-- Element 2 -->
            <div class="elementR">
                <p class="center"> <h4> Number of operations </h4> </p>

                <p class="center"> <?php echo ($CreditCount)?> </p>

                <p class="center"> <?php echo ($DebitCount)?> </p>

            </div>

            <!-- Element 3 -->
            <div class="elementR">
                <p class="center"> <h4> Total amount </h4> </p>

                <p class="center"> <?php echo ($CreditSum)?> </p>

                <p class="center"> <?php echo ($DebitSum)?> </p>
                
            </div>

        </div>
            
    </body>

</html>