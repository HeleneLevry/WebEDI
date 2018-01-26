<!-- do.autentification.php -->

<?php

// Validate variables to false
$ValidateLogin = false;
$ValidatePassword = false;
$RedirectSaisie = false;
$Error = '';
$connection;

if (parameterControl()){

	if (databaseConnection()) {
		if ( loginSearch($loginForm) ){
			if (pwdSearch($passwordForm)) {
				$RedirectSaisie = true;
			}
			else {
				$RedirectSaisie = false;
				$Error = 'pwdWrong';
			}
		}
		else {
			$RedirectSaisie = false;
			$Error = 'loginNotFound';
		}
	}
	else {
		$RedirectSaisie = false;
		$Error = 'errConnectionDB';
	}	

}
else {
	$RedirectSaisie = false;
	$Error = 'missingArg';
}

// ----- Redirection ----- //
/*// Redirect to saisie.php if correct identity
if ($ValidateLogin AND $ValidatePassword){
	header("Location: saisie.php?name=$nameFile&firstname=$firstnameFile&id=$idFile");
	exit();
}*/
// RedirectSaisie
if ($RedirectSaisie) {
	header("Location: index.php?OK!");
	exit();
}
// Missing arguments
else if (!$RedirectSaisie AND $Error == 'missingArg') {
	header("Location: index.php?error=missingArg");
	exit();
}
// Error connection database
else if (!$RedirectSaisie AND $Error == 'errConnectionDB') {
	header("Location: index.php?error=errConnectionDB");
	exit();
}
// Wrong login
else if (!$RedirectSaisie AND $Error == 'loginNotFound') {
	header("Location: index.php?error=loginNotFound");
	exit();
}
// Redirect to index.php with error message if wrong identity
else{
	header("Location: index.php?error=unknow");
	exit();
}


// --------------------------------------------------
// parameterControl
function parameterControl(){
	if (isset($_POST["login"]) AND isset($_POST["password"])){
		// Form values recovery
		$loginForm = $_POST["login"];
		$passwordForm = $_POST["password"];
		return true;
	}
	else {
		return false;
	}
}
// --------------------------------------------------


// --------------------------------------------------
// Database Connection
function databaseConnection(){
	try{
		$dbhost = 'mysql:host=localhost;dbname=webedi';
		$dbuser = 'root';
		$dbmdp = '';
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
		$connection = new PDO( $dbhost, $dbuser, $dbmdp, $options);
		return true;
	} catch (Exception $e){
		echo"ERROR : MySQL connection failed : ", $e->getMessage();
		die();
		return false;
	}
}
// --------------------------------------------------


// --------------------------------------------------
// Login search in database
function loginSearch($loginForm){
	$gabSearchLogin = "SELECT count(*) FROM users WHERE login LIKE ?";
	$prepSearchLogin = $connection->prepare($gabSearchLogin);
	$exeSearchLogin = $prepSearchLogin->execute(array($loginForm));
	$resultatLogin = $prepSearchLogin->fetch(PDO::FETCH_NUM);
	if ($resultatLogin[0] = 1) {
		echo 'true';
		exit;
	}
	else {
		echo 'false';
		exit;
	}
}
// --------------------------------------------------



/* to read a file - remplaced by database connection
	// Open utilisateurs.txt
	@ $infos_utilisateurs = fopen("utilisateurs.txt", "r");
	// Error if file not found
	if (!$infos_utilisateurs){
		echo ("ERROR : file utilisateurs.txt could not be opened");
		exit;
	}
	// Lock the file - share reading
	flock($infos_utilisateurs, 1);
	// Until the end of the file
	while((!feof($infos_utilisateurs)) AND (!$ValidateLogin) AND (!$ValidatePassword)){
		// Read the file lign by lign
		$ligne = fgets($infos_utilisateurs);
		// Split by ; and save the values
		list($idFile, $loginFile, $passwordFile, $nameFile, $firstnameFile) = explode(";", $ligne);

		// Test login
		if ($loginForm===$loginFile){
			$ValidateLogin=true;
			// Test password
			if ($passwordForm===$passwordFile){
				$ValidatePassword=true;
			}
			// If wrond password, ValidateLogin false
			else{
				$ValidateLogin=false;
			}
		}

	}
	// Unlock the file
	flock($infos_utilisateurs, 3);
	// Close utilisateurs.txt
	fclose($infos_utilisateurs);
*/


?>