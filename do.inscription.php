<!-- do.inscription.php -->

<?php

// Validate variables to false
$ValidateLogin=false;
$ValidatePassword=false;
$ValidateName=false;
$ValidateFirstname=false;

// Parameters control
if ( isset($_POST["login"]) AND isset($_POST["password"]) AND isset($_POST["passwordConfirm"]) AND isset($_POST["name"]) AND isset($_POST["firstname"])){

	// Form values recovery
	$loginForm = $_POST["login"];
	$passwordForm = $_POST["password"];
	$passwordConfirmForm = $_POST["passwordConfirm"];
	$nameForm = $_POST["name"];
	$firstnameForm = $_POST["firstname"];

	// Database connection
	try{
		$dbhost = 'mysql:host=localhost;dbname=webedi';
		$dbuser = 'root';
		$dbmdp = '';
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);
		$connection = new PDO( $dbhost, $dbuser, $dbmdp, $options);
	} catch (Exception $e){
		echo"ERROR : MySQL connection failed : ", $e->getMessage();
		die();
	}

	// Form values verification
	// Login
	$gabSearchLogin = "SELECT count(*) FROM users WHERE login LIKE ?";
	$prepSearchLogin = $connection->prepare($gabSearchLogin);
	$exeSearchLogin = $prepSearchLogin->execute(array($loginForm));
	$resultatLogin = $prepSearchLogin->fetch(PDO::FETCH_NUM);
	if ($resultatLogin[0] != 0) {
		header("Location: connection.php?error=loginExists");
		exit();
	}
	else{
		$ValidateLogin = true;
	}
	// Password
	if ($passwordForm == $passwordConfirmForm) {
		if (testPassword($passwordForm)){
			$ValidatePassword = true;
		}
		else {
			header("Location: connection.php?error=incorrectPassword");
			exit();
		}
	}
	else {
		header("Location: connection.php?error=pwdNotConfirm");
		exit();
	}
	$ValidateName = true;
	$ValidateFirstname = true;
}

// Missing field(s)
else{
	echo ("ERROR : Missing field(s)");
	exit;
}


// --------------------------------------------------
// ----- Redirection ----- //
// Redirect to saisie.php if correct identity
if ($ValidateLogin AND $ValidatePassword AND $ValidateName AND $ValidateFirstname){
	$datecur = date("Y-m-d");
	echo $datecur;
	// Insert into webedi database
	$gabInsertUser = 
	"INSERT INTO users (userId,login,password,name,firstname,attemps,connectionDate) values 
	(NULL, ?, ?, ?, ?, 0, ?)";
	$prepInsertUser = $connection->prepare($gabInsertUser);
	$exeInsertUser = $prepInsertUser->execute(array($loginForm, $passwordForm, $nameForm, $firstnameForm, $datecur));
	// Redirect to saisie.php (new user)
	header("Location: saisie.php?new=NewUser&name=$nameForm&firstname=$firstnameForm&id=$idFile");
	exit();
}
// Redirect to index.php with error message if wrong identity
else{
	header("Location: inscription.php?error=missingArg");
	exit();
}
// --------------------------------------------------


// --------------------------------------------------
// Test password
function testPassword($pwd)	{
	// Validate variables
	$lowercase = false;
	$uppercase = false;
	$number = false;
	// test length
	$lenPwd = strlen($pwd);
	if ($lenPwd < 8) {
		return false;
	}
	// If length OK
	else {
		// test lowercase, uppercase, number
		for($i=0 ; $i<$lenPwd ; $i++) {
			$car = $pwd[$i];
			if ($car>='a' && $car<='z'){
				$lowercase = true;
			}
			else if ($car>='A' && $car<='Z'){
				$uppercase = true;
			}
			else if ($car>='0' && $car<='9'){
				$number = true;
			}
		}
		// If contain lower/upper case and number : true
		if ($lowercase AND $uppercase AND $number) {
			return true;
		}
		// else : false
		else{
			return false;
		}
	}  
}
// --------------------------------------------------


?>