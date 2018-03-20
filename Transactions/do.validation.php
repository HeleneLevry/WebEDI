<!-- do.validation.php -->

<!--?php require "session.php" ?-->

<?php

	// login in parameter
if (isset($_GET['login'])){
    	// Form values recovery
	$loginForm = $_GET['login'];

		// Database connection
	try{
		/*$dbhost = 'mysql:host=localhost;dbname=webedi';
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

		// Login search
		$gabSearchLogin = "SELECT count(*) FROM users WHERE login LIKE ?";
		$prepSearchLogin = $connection->prepare($gabSearchLogin);
		$exeSearchLogin = $prepSearchLogin->execute(array($loginForm));
		$resultatLogin = $prepSearchLogin->fetch(PDO::FETCH_NUM);
		if ($resultatLogin[0] == 1) {

					// Active account
			$gabActive = "UPDATE users SET active=? WHERE login LIKE ?";
			$prepActive = $connection->prepare($gabActive);
			$exeActive = $prepActive->execute(array(1, $loginForm));

			// Active account
			$gabActive = "SELECT active FROM users WHERE login LIKE ?";
			$prepActive = $connection->prepare($gabActive);
			$exeActive = $prepActive->execute(array($loginForm));
			$resultatActive = $prepActive->fetch(PDO::FETCH_NUM);
			if ($resultatActive[0] == 1) {

				header("Location: connection.php?error=accountActivation");
				exit();


			// Find if active account
			}
			else{
				$RedirectSaisie = false;
				$Error = 'NotActive';
			}


    	// Login search	
		}
		else {
			$RedirectSaisie = false;
			$Error = 'loginNotFound';
		}

    	// Database connection
	} catch (Exception $e){
		echo"ERROR : MySQL connection failed : ", $e->getMessage();
		$RedirectSaisie = false;
		$Error = 'errConnectionDB';
		die();
	}


}
    // No parameters (login)
else{
	echo '<p class="err">No login found in the URL</p>';
	exit();
}

// Add redirect error !!!