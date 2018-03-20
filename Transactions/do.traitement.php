<!-- do.traitement.php -->

<?php require "session.php" ?>

<?php
// Session expired
if($_SESSION['last_activity'] < time()-$expire_time){
	header("Location: connection.php?error=sessionExpired");
	exit();
}
$_SESSION['last_activity'] = time();

// Nb transaction
$nbTransaction = 5;
// date
$date = time();
// Error
$Error = '';
// $RedirectResultat
$RedirectResultat = false;
// $userId
$userId = '';



// Form parameters recovery
for($i=0;$i<$nbTransaction;$i++){
	${'transactionType'.$i} = $_POST["transactionType".$i];
	${'amount'.$i} = $_POST["amount".$i];
	${'currency'.$i} = $_POST["currency".$i];
	${'originAccount'.$i} = $_POST["originAccount".$i];
	${'destinationAccount'.$i} = $_POST["destinationAccount".$i];
}
// Parameters control
if (isset($_SESSION['userId'])){
	$userId = $_SESSION['userId'];

	//  && $_SESSION['active'] == 1

	// Database connection
	try{
/*		$dbhost = 'mysql:host=localhost;dbname=webedi';
		$dbuser = 'root';
		$dbmdp = '';
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);
		$connection = new PDO( $dbhost, $dbuser, $dbmdp, $options);*/
		$connection = new PDO(
			"mysql:host=" . getenv("MYSQL_ADDON_HOST") . ";dbname=" . getenv("MYSQL_ADDON_DB"),
	    	getenv("MYSQL_ADDON_USER"),
	    	getenv("MYSQL_ADDON_PASSWORD"),
	    	array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
		);
	} catch (Exception $e){
		echo"ERROR : MySQL connection failed : ", $e->getMessage();
		$RedirectSaisie = false;
		$Error = 'errConnectionDB';
		die();
	}

	/*echo'test<br>';
	echo($date.''.
		$transactionType0.''.
		$amount0.''.
		$currency0.''.
		$originAccount0.''. 
		$destinationAccount0);*/

	// Save transations
	try{
		$connection->beginTransaction();
		for($i=0;$i<$nbTransaction;$i++){
			if ( ((${'transactionType'.$i} == 'credit' ) OR (${'transactionType'.$i} == 'debit' )) AND (floatval(${'amount'.$i}) != 0) AND (${'originAccount'.$i} AND ${'destinationAccount'.$i}) ){
				$gSaveTransaction = 
				"INSERT INTO operations(opeId, batch, type, amount, originAccount, destinationAccount, userId)
				values (NULL, ?, ?, ?, ?, ?, ?);";
				$pSaveTransatcion = $connection->prepare($gSaveTransaction);
				//echo("<br> batch : " . $date. ", type : ". ${'transactionType'.$i}. ", amount : ". floatval(${'amount'.$i}). ", originAccount : ". ${'originAccount'.$i}. ", destinationAccount : ". ${'destinationAccount'.$i} . ", userId : ". $userId);
				//echo("<br> transaction ok : " . $i);
				$eSaveTransaction = $pSaveTransatcion->execute(
					array($date, ${'transactionType'.$i}, floatval(${'amount'.$i}), ${'originAccount'.$i}, ${'destinationAccount'.$i}, $userId)
					);
			}
		}
		$connection->commit();
		$RedirectResultat = true;
	} catch (Exception $e){
		$connection->rollback();
		$RedirectSaisie = false;
		$Error = 'transactionFailed';
		echo"ERROR : MySQL connection failed : ", $e->getMessage();
		die();
	}
}

// Parameters control
else{
	$RedirectResultat = false;
	$Error = 'missingArgFromSaisie';
}


// ----- Redirection ----- //
// Redirect to saisie.php if correct identity
if ($RedirectResultat) {
	header("Location: resultat.php?date=$date");
	exit();
}
// Missing arguments
else if (!$RedirectResultat AND $Error == 'missingArgFromSaisie') {
	header("Location: saisie.php?error=missingArgFromSaisie");
	exit();
}
// Missing arguments
else if (!$RedirectResultat AND $Error == 'transactionFailed') {
	header("Location: saisie.php?error=transactionFailed");
	exit();
}


?>