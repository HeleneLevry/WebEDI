<!-- do.autentification.php -->

<?php 


// -------------------- SESSION / COOKIES --------------------

// session.php
require "session.php";
// setcookie
setcookie('WebEDI_login', $_POST["login"], time() + 30*24*3600, null, null, false, true);


// -------------------- PROGRAMME --------------------

// ----- Treatment -----
// Init redirection on false
$RedirectSaisie = false;
// parameterControl
if (parameterControl()){
	// dbConnect
	if (dbConnect()){
		$connection = dbConnect();
		// loginSearch
		if (loginSearch()){
			// activeSearch
			if (activeSearch()) {
				// nbrAttempsSearch
				if (nbrAttempsSearch()){
					// passwordVerify
					if (passwordVerify()){
						// resetNbAttemps
						resetNbAttemps();
						// connectionDateUpdate
						connectionDateUpdate();
						// saveUSerInfos
						saveUSerInfos();
						// Allow redirection
						$RedirectSaisie = true;
					// passwordVerify
					}
				// nbrAttempsSearch
				}
			// activeSearch
			}
		// loginSearch	
		}
	// dbConnect	
	}
// parameterControl	
}

// ----- Redirection -----
if ($RedirectSaisie) {
	header("Location: saisie.php");
	exit();
}
elseif (isset($Error)) {
	redirectError();
}
else{
	echo('Issue to redirect');
	exit();
}


// -------------------- FUNCTIONS --------------------

// parameterControl
function parameterControl(){
	if ( isset($_POST["login"]) AND isset($_POST["password"]) ) {
		global $loginForm, $passwordForm;
		$loginForm = $_POST["login"];
		$passwordForm = $_POST["password"];
		return true;
	}
	else {
		global $Error, $RedirectSaisie;
		$RedirectSaisie = false;
		$Error = 'missingArg';
		return false;
	}	
}
// dbConnect
function dbConnect(){
	try{
		$dbhost = 'mysql:host=localhost;dbname=webedi';
		$dbuser = 'root';
		$dbmdp = '';
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
		$connection = new PDO( $dbhost, $dbuser, $dbmdp, $options);
		return $connection;
	} catch (Exception $e){
		global $Error, $RedirectSaisie;
		echo"ERROR : MySQL connection failed : ", $e->getMessage();
		$RedirectSaisie = false;
		$Error = 'errConnectionDB';
		return false;
	}
}
// loginSearch
function loginSearch(){
	global $connection, $loginForm;
	$gabSearchLogin = "SELECT count(*) FROM users WHERE login LIKE ?";
	$prepSearchLogin = $connection->prepare($gabSearchLogin);
	$exeSearchLogin = $prepSearchLogin->execute(array($loginForm));
	$resultatLogin = $prepSearchLogin->fetch(PDO::FETCH_NUM);
	if ($resultatLogin[0] == 1) {
		return true;
	}
	else {
		global $Error, $RedirectSaisie;
		$RedirectSaisie = false;
		$Error = 'loginNotFound';
		return false;
	}
}
// activeSearch
function activeSearch(){
	global $connection, $loginForm;
	$gabActive = "SELECT active FROM users WHERE login LIKE ?";
	$prepActive = $connection->prepare($gabActive);
	$exeActive = $prepActive->execute(array($loginForm));
	$resultatActive = $prepActive->fetch(PDO::FETCH_NUM);
	if ($resultatActive[0] == 1) {
		return true;
	}
	else {
		global $Error, $RedirectSaisie;
		$RedirectSaisie = false;
		$Error = 'NotActive';
		return false;
	}
}
// nbrAttempsSearch
function nbrAttempsSearch(){
	global $connection, $loginForm;
	$gabNbrAttemps = "SELECT attemps FROM users WHERE login LIKE ?";
	$prepNbrAttemps = $connection->prepare($gabNbrAttemps);
	$exeNbrAttemps = $prepNbrAttemps->execute(array($loginForm));
	$resultatNbrAttemps = $prepNbrAttemps->fetch(PDO::FETCH_NUM);
	if ($resultatNbrAttemps[0] <3) {
		$gabUpdtNbrAttemps = "UPDATE users set attemps=? WHERE login LIKE ?";
		$prepUpdtNbrAttemps = $connection->prepare($gabUpdtNbrAttemps);
		$exeUpdtNbrAttemps = $prepUpdtNbrAttemps->execute(array($resultatNbrAttemps[0]+1, $loginForm));
		return true;
	}
	else {
		global $Error, $RedirectSaisie;
		$RedirectSaisie = false;
		$Error = 'TooManyAttemps';
		return false;
	}
}
// passwordVerify
function passwordVerify(){
	global $connection, $loginForm, $passwordForm;
	$gabSearchPwd = "SELECT password FROM users WHERE login LIKE ?";
	$prepSearchPwd = $connection->prepare($gabSearchPwd);
	$exeSearchPwd = $prepSearchPwd->execute(array($loginForm));
	$resultatPwd = $prepSearchPwd->fetch(PDO::FETCH_NUM);
	if ($resultatPwd[0] == md5($passwordForm)) {
		return true;
	}
	else {
		global $Error, $RedirectSaisie;
		$RedirectSaisie = false;
		$Error = 'pwdWrong';
		return false;
	}
}
// resetNbAttemps
function resetNbAttemps(){
	global $connection, $loginForm;
	$gabUpdtNbrAttemps = "UPDATE users set attemps=? WHERE login LIKE ?";
	$prepUpdtNbrAttemps = $connection->prepare($gabUpdtNbrAttemps);
	$exeUpdtNbrAttemps = $prepUpdtNbrAttemps->execute(array(0, $loginForm));
}
// connectionDateUpdate
function connectionDateUpdate(){
	global $connection;
	$datecur = date("Y-m-d");
	$gabDateConnect = "UPDATE users set connectionDate = ?";
	$prepDateConnect = $connection->prepare($gabDateConnect);
	$exeDateConnect = $prepDateConnect->execute(array($datecur));
	$_SESSION['logging_time'] = $datecur;
}
// saveUSerInfos
function saveUSerInfos(){
	global $connection, $loginForm, $passwordForm;
	$gabUserInfo = "SELECT userId, name, firstName, email FROM users WHERE login LIKE ?";
	$prepUserInfo = $connection->prepare($gabUserInfo);
	$exeUserInfo = $prepUserInfo->execute(array($loginForm));
	$resultatUserInfo = $prepUserInfo->fetch(PDO::FETCH_NUM);
	// Session variable
	$_SESSION['userId'] = $resultatUserInfo[0];
	$_SESSION['login'] = $loginForm;
	$_SESSION['password'] = $passwordForm;
	$_SESSION['name'] = $resultatUserInfo[1];
	$_SESSION['firstname'] = $resultatUserInfo[2];
	$_SESSION['email'] = $resultatUserInfo[3];
	$_SESSION['last_activity'] = time();
}
// redirectError
function redirectError(){
	global $Error;
	switch($Error) {
    	// parameterControl
		case 'missingArg':
		header("Location: connection.php?error=missingArg");
		exit();
		break;
	    // dbConnect
		case 'errConnectionDB':
		header("Location: connection.php?error=errConnectionDB");
		exit();
		break;
	    // loginSearch
		case 'loginNotFound':
		header("Location: connection.php?error=loginNotFound");
		exit();
		break;
	    // activeSearch
		case 'NotActive':
		header("Location: connection.php?error=NotActive");
		exit();
		break;
	    // nbrAttempsSearch
		case 'TooManyAttemps':
		header("Location: connection.php?error=TooManyAttemps");
		exit();
		break;
	    // passwordVerify
		case 'pwdWrong':
		header("Location: connection.php?error=pwdWrong");
		exit();
		break;
		default:
		header("Location: connection.php?error=unknow");
		exit();
	}
}

?>