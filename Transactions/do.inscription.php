<!-- do.inscription.php -->

<?php

// -------------------- PROGRAMME --------------------

// ----- Treatment -----
// Init redirection on false
$RedirectSaisie = false;
// parameterControl
if (parameterControl()){
	// dbConnect
	if (dbConnect()){
		// loginSearch
		if (loginSearch()){
			// emailConfirm
			if (emailConfirm()){
				// passwordConfirm
				if (passwordConfirm()){
					// passwordTest
					if (passwordTest()){
						// passwordHash
						if (passwordHash()){
							// insertInDB
							insertInDB();
							// sendMail
							if (sendmail()){
							// sendMail
							}							
						// passwordHash	
						}
					// passwordTest	
					}
				// passwordConfirm	
				}
			// emailConfirm	
			}
		// loginSearch	
		}
	// dbConnect
	}
// parameterControl
}

// ----- Redirection -----
if ($Redirect) {
	header("Location: validInscription.php");
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

// ----- Treatment -----
// parameterControl
function parameterControl(){
	// Parameters control
	if ( 
		isset($_POST["name"]) AND
		isset($_POST["firstname"]) AND
		isset($_POST["login"]) AND 
		isset($_POST["email"]) AND
		isset($_POST["emailConfirm"]) AND
		isset($_POST["password"]) AND 
		isset($_POST["passwordConfirm"])
	){
		global $nameForm, $firstnameForm, $loginForm, $emailForm, $emailConfirmForm, $passwordForm, $passwordConfirmForm;
		// Form values recovery
		$nameForm = $_POST["name"];
		$firstnameForm = $_POST["firstname"];
		$loginForm = $_POST["login"];
		$emailForm = $_POST["email"];
		$emailConfirmForm = $_POST["emailConfirm"];
		$passwordForm = $_POST["password"];
		$passwordConfirmForm = $_POST["passwordConfirm"];
		return true;
	}
	else {
		global $Error, $Redirect;
		$Redirect = false;
		$Error = 'missingArg';
		return false;
	}	
}
// dbConnect
function dbConnect(){
	try{
		global $connection;
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
		return $connection;
	} catch (Exception $e){
		global $Error, $Redirect;
		echo"ERROR : MySQL connection failed : ", $e->getMessage();
		$Redirect = false;
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
	if ($resultatLogin[0] == 0) {
		return true;
	}
	else {
		global $Error, $Redirect;
		$Redirect = false;
		$Error = 'loginExists';
		return false;
	}
}
// emailConfirm
function emailConfirm(){
	global $emailForm, $emailConfirmForm;
	if ($emailForm == $emailConfirmForm){
		return true;
	}
	else {
		global $Error, $Redirect;
		$Redirect = false;
		$Error = 'emailNotConfirm';
		return false;
	}
}
// passwordConfirm
function passwordConfirm(){
	global $passwordForm, $passwordConfirmForm;
	if ($passwordForm == $passwordConfirmForm){
		return true;
	}
	else {
		global $Error, $Redirect;
		$Redirect = false;
		$Error = 'emailNotConfirm';
		return false;
	}
}
// passwordTest
function passwordTest()	{
	global $passwordForm;
	// Validate variables
	$lowercase = false;
	$uppercase = false;
	$number = false;
	// test length
	$lenPwd = strlen($passwordForm);
	if ($lenPwd < 8) {
		return false;
	}
	// If length OK
	else {
		// test lowercase, uppercase, number
		for($i=0 ; $i<$lenPwd ; $i++) {
			$car = $passwordForm[$i];
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
// passwordHash
function passwordHash(){
	global $passwordForm, $passwordHash;
	$passwordHash = md5($passwordForm);
	if (!$passwordHash){
		global $Error, $Redirect;
		$Redirect = false;
		$Error = 'passwordNotHashed';
		return false;
	} else{
		return true;
	}
}
// insertInDB
function insertInDB(){
	global $connection, $loginForm, $passwordHash, $nameForm, $firstnameForm, $emailForm;
	$datecur = date("Y-m-d");
	$gabInsertUser = 
	"INSERT INTO users (userId,login,password,name,firstname,email, attemps,connectionDate, active) values 
	(NULL, ?, ?, ?, ?, ?, 0, ?, 0)";
	$prepInsertUser = $connection->prepare($gabInsertUser);
	$exeInsertUser = $prepInsertUser->execute(array($loginForm, $passwordHash, $nameForm, $firstnameForm, $emailForm, $datecur));
}
//sendMail
function sendMail(){
	global $loginForm, $nameForm, $emailForm;
	$entetes = 
		'Content-type: text/html; charset=utf-8' . "\r\n" . 
		'From: webedi@webedi.com' . "\r\n" .
		'Reply-To: webedi@webedi.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	$objet = 'Web-EDI - Registration confirmation';
	$contenu = 
		'<html>
			<head>
				<title> Registration confirmation to Web-EDI </title>
			</head>
			<body>
				<p> Hello Mr/Mrs ' . $nameForm . ',</p>
				<p> 
					Please click on the following link to activate your account on Web-EDI :
					<a href="http://localhost/web-edi/do.validation.php?login='.$loginForm.'"> 
					Activate my account </a>
					<br>
					Your login to this website is: ' . $loginForm . '.
				</p>
			</body>
		</html>';
	if (mail($emailForm, $objet, $contenu, $entetes)){
		global $Redirect;
		$Redirect = true;
	}
	else{
		global $Error, $Redirect;
		$Redirect = false;
		$Error = 'mailNotSent';
		return false;
	}
}

// ----- Redirection -----
// redirectError
function redirectError(){
	global $Error;
	switch($Error) {
    	// parameterControl
		case 'missingArg':
		header("Location: inscription.php?error=missingArg");
		exit();
		break;
		// dbConnect
		case 'errConnectionDB':
		header("Location: inscription.php?error=errConnectionDB");
		exit();
		break;
		// loginSearch
		case 'loginExists':
		header("Location: inscription.php?error=loginExists");
		exit();
		break;
		// emailConfirm
		case 'emailNotConfirm':
		header("Location: inscription.php?error=emailNotConfirm");
		exit();
		break;
		// passwordConfirm
		case 'pwdNotConfirm':
		header("Location: inscription.php?error=pwdNotConfirm");
		exit();
		break;
		// passwordTest
		case 'incorrectPassword':
		header("Location: inscription.php?error=incorrectPassword");
		exit();
		break;
		// passwordHash
		case 'passwordNotHashed':
		header("Location: inscription.php?error=passwordNotHashed");
		exit();
		break;
		// sendMail
		case 'mailNotSent':
		header("Location: inscription.php?error=mailNotSent");
		exit();
		break;
		// default
		default:
		header("Location: inscription.php?error=unknow");
		exit();
	}
}

?>