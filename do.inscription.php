<!-- do.inscription.php -->

<!--?php require "session.php" ?-->

<?php

// Validate variables to false
$ValidateName=false;
$ValidateFirstname=false;
$ValidateLogin=false;
$ValidateEmail=false;
$ValidatePassword=false;

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

	// Form values recovery
	$nameForm = $_POST["name"];
	$firstnameForm = $_POST["firstname"];
	$loginForm = $_POST["login"];
	$emailForm = $_POST["email"];
	$emailConfirmForm = $_POST["emailConfirm"];
	$passwordForm = $_POST["password"];
	$passwordConfirmForm = $_POST["passwordConfirm"];

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
		header("Location: inscription.php?error=loginExists");
		exit();
	}
	else{
		$ValidateLogin = true;
	}
	// Email
	if ($emailForm == $emailConfirmForm){
		$ValidateEmail = true;
	}
	else {
		header("Location: inscription.php?error=emailNotConfirm");
		exit();
	}
	// Password
	if ($passwordForm == $passwordConfirmForm) {
		if (testPassword($passwordForm)){
			$passwordHash = md5($passwordForm);
			if (!$passwordHash){
				header("Location: inscription.php?error=passwordNotHashed");
				exit();
			} else{
				$ValidatePassword = true;
			}
		}
		else {
			header("Location: inscription.php?error=incorrectPassword");
			exit();
		}
	}
	else {
		header("Location: inscription.php?error=pwdNotConfirm");
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
if ($ValidateName AND $ValidateFirstname AND $ValidateLogin AND $ValidateEmail  AND $ValidatePassword){
	$datecur = date("Y-m-d");
	// Insert into webedi database
	$gabInsertUser = 
	"INSERT INTO users (userId,login,password,name,firstname,email, attemps,connectionDate, active) values 
	(NULL, ?, ?, ?, ?, ?, 0, ?, 0)";
	$prepInsertUser = $connection->prepare($gabInsertUser);
	$exeInsertUser = $prepInsertUser->execute(array($loginForm, $passwordHash, $nameForm, $firstnameForm, $emailForm, $datecur));

// <a href="http://localhost/web-edi/do.validation.php/<?php echo($loginForm)"> Activate my account </a>
	// Send an email
	//sendMail2();


	$objet = 'Web-EDI - Confirmation inscription';

	$contenu = '
		<html>
			<head>
				<title> Confirmation d\'inscription à Web-EDI </title>
			</head>
			<body>
				<p> Bonjour Mr/Mme ' . $nameForm . ',</p>
				<p> 
					Veuillez cliquer sur le lien suivant pour valider votre inscription sur Web-EDI :
					<a href="http://localhost/web-edi/do.validation.php?login='.$loginForm.'"> 
					Activate my account </a>
					
				</p>
			</body>
		</html>';

	$entetes = 
		'Content-type: text/html; charset=utf-8' . "\r\n" . 
		'From: email@domain.tld' . "\r\n" .
		'Reply-To: email@domain.tld' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

		echo($emailForm . ' ' . $objet . ' ' . $contenu . ' ' . $entetes);

	if (mail($emailForm, $objet, $contenu, $entetes)){
		echo("mail sent !\n");
	}
	else{
		echo("mail not sent !\n");
	}
	
	//sendMail($nameForm, $emailForm, $loginForm);

	/*$objet = 'Webedi - Confirmation inscription';
	$contenu = '
		<html>
			<head>
				<title> Confirmation inscription WebEDI </title>
			</head>
			<body>
				<p> Bonjour Mr/Mme ' . $nameForm . '</p>
				<p> Vous êtes désormais inscrits </p>
			</body>
		</html>';
	$entetes = 
		'Content-type: text/html; charset=utf-8' . "\r\n" . 
		'From: email@domain.tld' . "\r\n" .
		'Reply-To: email@domain.tld' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	if (mail($emailForm, $objet, $contenu, $entetes)){
		echo("mail sent !");
	}
	else{
		echo("mail not sent !");
	}


	exit();*/


	// Redirect to saisie.php (new user)
	//header("Location: saisie.php?new=NewUser&name=$nameForm&firstname=$firstnameForm&id=$idFile");
	header("Location: validInscription.php");
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




// --------------------------------------------------
// Send mail
function sendMail($userName, $userEmail, $login)	{

	$objet = 'Web-EDI - Confirmation inscription';

	$contenu = '
		<html>
			<head>
				<title> Confirmation d\'inscription à Web-EDI </title>
			</head>
			<body>
				<p> Bonjour Mr/Mme ' . $userName . ',</p>
				<p> 
					Veuillez cliquer sur le lien suivant pour valider votre inscription sur Web-EDI : 
					<a href="localhost/web-edi/do.validation.php/<?php echo($login)?>"> Activate my account </a>
				</p>
			</body>
		</html>';

	$entetes = 
		'Content-type: text/html; charset=utf-8' . "\r\n" . 
		'From: email@domain.tld' . "\r\n" .
		'Reply-To: email@domain.tld' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	if (mail($userEmail, $objet, $contenu, $entetes)){
		echo("mail sent !\n");
	}
	else{
		echo("mail not sent !\n");
	}
	exit();
	//connection.php?error=accountActivation


}
// --------------------------------------------------


// --------------------------------------------------
// Send mail
function sendMail2()	{

	$destinataire = $_POST["email"];

	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $destinataire)){
		$passage_ligne = "\r\n";
	} else {
		$passage_ligne = "\n";
	}

	//=====Déclaration des messages au format texte et au format HTML.
	$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
	$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
	//==========

	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	 
	//=====Définition du sujet.
	$sujet = "Hey mon ami !";
	//=========
	 
	//=====Création du header de l'e-mail.
	$header = "From: \"WeaponsB\"<weaponsb@mail.fr>".$passage_ligne;
	$header.= "Reply-to: \"WeaponsB\" <weaponsb@mail.fr>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	 
	//=====Création du message.
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_txt.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_html.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//==========

	/*$destinataire = $_POST["email"];
	$expediteur = 'helenelevr@gmail.com';
	$objet = 'Test WebEDI';
	$headers  = 'MIME-Version: 1.0' . "\n";
	$headers .= 'Reply-To: '.$expediteur."\n";
	$headers .= 'From: "Nom_de_expediteur"<'.$expediteur.'>'."\n";
	$headers .= 'Delivered-to: '.$destinataire."\n";
	$message = 'Un Bonjour de Developpez.com!';*/

	// mail($destinataire, $objet, $message, $headers);

	if (mail($destinataire, $sujet, $message, $header)) // Envoi du message
	{
	    echo "Votre message a bien été envoyé\n";
	}
	else // Non envoyé
	{
	    echo "Votre message n'a pas pu être envoyé\n";
	}


	 
	/*//=====Envoi de l'e-mail.
	mail($mail,$sujet,$message,$header);
	//==========*/

	// echo("mail sent");
	// exit();


}





?>