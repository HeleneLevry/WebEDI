<!-- do.update.php -->

<?php 

// -------------------- PROGRAMME --------------------

// ----- Treatment -----
//getInFile("USD");
$rateUSD = getRate("USD");
$rateGBP = getRate("GBP");
// ----- Redirection -----
header("Location: taux.php?USD=".$rateUSD."&GBP=".$rateGBP);
exit();

// -------------------- FUNCTIONS --------------------

// ----- Treatment -----
// getData
function getData($devise){
	// init()
	$connexion = curl_init();
	//opt
	curl_setopt($connexion, CURLOPT_URL, "http://xe.com/currencyconverter/convert/?Amount=1&From=".$devise."&To=EUR");
	curl_setopt($connexion, CURLOPT_RETURNTRANSFER, true);
	// get in var
	$data = curl_exec($connexion);
	// close connexion
	curl_close($connexion);
	// return data
	return $data;
}
// getValue
function getValue($data){
	// Find ligne
	$patternLign = "#span class='uccResultAmount'>[0-9\.]+</span#";
	preg_match($patternLign, $data, $resultLign);
	// Find value
	$patternValue = '#[0-9\.]+#';
	preg_match($patternValue, $resultLign[0], $resultValue);
	// Return result
	return $resultValue[0];
}
//getRate
function getRate($devise){
	$dataUSD = getData($devise);
	$valueUSD = getValue($dataUSD);
	return $valueUSD;
}
// getInFile
function getInFile($devise){
	// output declaration
	$outputFile = "C:\wamp64\www\Web-EDI\Taux\output.html";
	// open file
	$fp = fopen($outputFile, "w");
	// init connection
	$connexionFile = curl_init();
	// setOPT
	curl_setopt($connexionFile, CURLOPT_FILE, $fp);
	curl_setopt($connexionFile, CURLOPT_URL, "http://xe.com/currencyconverter/convert/?Amount=1&From=".$devise."&To=EUR");
	// Exec
	curl_exec($connexionFile);
	// Close
	curl_close($connexionFile);
}

// ----------------------------------------

?>
