<!-- do.autentification.php -->

<?php require "session.php" ?>

<?php
    setcookie('WebEDI_login', $_POST["login"], time() + 30*24*3600, null, null, false, true);
?>

<?php

// Validate variables to false
$ValidateLogin = false;
$ValidatePassword = false;
$RedirectSaisie = false;
$Error = '';

// Parameter control
if (isset($_POST["login"]) AND isset($_POST["password"])){
	
	// Form values recovery
	$loginForm = $_POST["login"];
	$passwordForm = $_POST["password"];

	// Database connection
	try{
		$dbhost = 'mysql:host=localhost;dbname=webedi';
		$dbuser = 'root';
		$dbmdp = '';
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);
		$connection = new PDO( $dbhost, $dbuser, $dbmdp, $options);

		// Active account
		$gabActive = "SELECT active FROM users WHERE login LIKE ?";
		$prepActive = $connection->prepare($gabActive);
		$exeActive = $prepActive->execute(array($loginForm));
		$resultatActive = $prepActive->fetch(PDO::FETCH_NUM);
		if ($resultatActive[0] == 1) {

			// Find nb attemps
			$gabNbrAttemps = "SELECT attemps FROM users WHERE login LIKE ?";
			$prepNbrAttemps = $connection->prepare($gabNbrAttemps);
			$exeNbrAttemps = $prepNbrAttemps->execute(array($loginForm));
			$resultatNbrAttemps = $prepNbrAttemps->fetch(PDO::FETCH_NUM);
			if ($resultatNbrAttemps[0] <3) {

				// Login search
				$gabSearchLogin = "SELECT count(*) FROM users WHERE login LIKE ?";
				$prepSearchLogin = $connection->prepare($gabSearchLogin);
				$exeSearchLogin = $prepSearchLogin->execute(array($loginForm));
				$resultatLogin = $prepSearchLogin->fetch(PDO::FETCH_NUM);
				if ($resultatLogin[0] == 1) {

					// Nb attemps Update
					$gabUpdtNbrAttemps = "UPDATE users set attemps=? WHERE login LIKE ?";
					$prepUpdtNbrAttemps = $connection->prepare($gabUpdtNbrAttemps);
					$exeUpdtNbrAttemps = $prepUpdtNbrAttemps->execute(array($resultatNbrAttemps[0]+1, $loginForm));

					// Password verification
					$gabSearchPwd = "SELECT password FROM users WHERE login LIKE ?";
					$prepSearchPwd = $connection->prepare($gabSearchPwd);
					$exeSearchPwd = $prepSearchPwd->execute(array($loginForm));
					$resultatPwd = $prepSearchPwd->fetch(PDO::FETCH_NUM);

					if ($resultatPwd[0] == md5($passwordForm)) {

						// Reset nb attemps
						$exeUpdtNbrAttemps = $prepUpdtNbrAttemps->execute(array(0, $loginForm));

						// Change last connection date
						$datecur = date("Y-m-d");
						$gabDateConnect = "UPDATE users set connectionDate = ?";
						$prepDateConnect = $connection->prepare($gabDateConnect);
						$exeDateConnect = $prepDateConnect->execute(array($datecur));

						// Find infos 
						$gabUserInfo = "SELECT userId, name, firstName, email FROM users WHERE login LIKE ?";
						$prepUserInfo = $connection->prepare($gabUserInfo);
						$exeUserInfo = $prepUserInfo->execute(array($loginForm));
						$resultatUserInfo = $prepUserInfo->fetch(PDO::FETCH_NUM);
						$userIdDB = $resultatUserInfo[0];
						$nameDB = $resultatUserInfo[1];
						$firstnameDB = $resultatUserInfo[2];
						$emailDB = $resultatUserInfo[3];

						// Allow redirection
						$RedirectSaisie = true;
						$_SESSION['userId'] = $userIdDB;
						$_SESSION['login'] = $loginForm;
						$_SESSION['password'] = $passwordForm;
						$_SESSION['name'] = $nameDB;
						$_SESSION['firstname'] = $firstnameDB;
						$_SESSION['email'] = $emailDB;
						$_SESSION['logging_time'] = $datecur;
						$_SESSION['last_activity'] = time();


						/*echo($_SESSION['userId'] .", ". $_SESSION['login'].", ". $_SESSION['password'].", ".  $_SESSION['name'].", ". $_SESSION['firstname'] . ", " . $_SESSION['email']);
						exit();*/
						//$_COOKIE['login'] = $loginForm;

					}

					// Password verification
					else{
						$RedirectSaisie = false;
						$Error = 'pwdWrong';
					}

				// Login search	
				}
				else {
					$RedirectSaisie = false;
					$Error = 'loginNotFound';
				}

			// 	Find nb attemps
			}
			else {
				$RedirectSaisie = false;
				$Error = 'TooManyAttemps';
			}

		// Find if active account
		}
		else{
			$RedirectSaisie = false;
			$Error = 'NotActive';
		}

	// Database connection
	} catch (Exception $e){
		echo"ERROR : MySQL connection failed : ", $e->getMessage();
		$RedirectSaisie = false;
		$Error = 'errConnectionDB';
		die();
	}

// Parameter control	
}
else {
	$RedirectSaisie = false;
	$Error = 'missingArg';
}

// ----- Redirection ----- //
// Redirect to saisie.php if correct identity
if ($RedirectSaisie) {
	header("Location: saisie.php?name=$nameDB&firstname=$firstnameDB&id=$userIdDB");
	exit();
}
// Missing arguments
else if (!$RedirectSaisie AND $Error == 'missingArg') {
	header("Location: connection.php?error=missingArg");
	exit();
}
// Error connection database
else if (!$RedirectSaisie AND $Error == 'errConnectionDB') {
	header("Location: connection.php?error=errConnectionDB");
	exit();
}
// not active account
else if (!$RedirectSaisie AND $Error == 'NotActive') {
	header("Location: connection.php?error=NotActive");
	exit();
}
// Too many attemps
else if (!$RedirectSaisie AND $Error == 'TooManyAttemps') {
	header("Location: connection.php?error=TooManyAttemps");
	exit();
}
// Wrong login
else if (!$RedirectSaisie AND $Error == 'loginNotFound') {
	header("Location: connection.php?error=loginNotFound");
	exit();
}
// Wrong password
else if (!$RedirectSaisie AND $Error == 'pwdWrong') {
	header("Location: connection.php?error=pwdWrong");
	exit();
}
// Redirect to connection.php with error message if wrong identity
else{
	header("Location: connection.php?error=unknow");
	exit();
}


?>