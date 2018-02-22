<!-- do.resentmail.php -->

<?php

// -------------------- PROGRAMME --------------------

// ----- Treatment -----
// parameterControl
if (parameterControl()){
	// sendMail
	sendMail();
// parameterControl	
}

// ----- Redirection -----
if ($Redirect) {
	header("Location: connection.php");
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
	if ( isset($_POST["login"]) AND isset($_POST["email"]) ) {
		global $loginForm, $emailForm;
		$loginForm = $_POST["login"];
		$emailForm = $_POST["email"];
		return true;
	}
	else {
		global $Error, $Redirect;
		$Redirect = false;
		$Error = 'missingArg';
		return false;
	}	
}
//sendMail
function sendMail(){
	global $loginForm, $emailForm;
	$headers = 
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
				<p> Hi ' . $loginForm . ',</p>
				<p> 
					Please click on the following link to activate your account on Web-EDI :
					<a href="http://localhost/web-edi/do.validation.php?login='.$loginForm.'"> 
					Activate my account </a>
					<br>
					Your login to this website is: ' . $loginForm . '.
				</p>
			</body>
		</html>';
	if (mail($emailForm, $objet, $contenu, $headers)){
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
		header("Location: resentmail.php?error=missingArg");
		exit();
		break;
		// sendMail
		case 'mailNotSent':
		header("Location: resentmail.php?error=mailNotSent");
		exit();
		break;
		// default
		default:
		header("Location: resentmail.php?error=unknow");
		exit();
	}
}

?>