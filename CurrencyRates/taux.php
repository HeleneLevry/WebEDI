<!--taux.php-->

<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="style.css" />
	<title>taux.php</title>
</head>

<body>

	<!-- Title -->
	<h1>Curency rates</h1>
	<hr>
	<br/>

	<!-- Error messages -->
	<?php
	if (parameterControl()){
	?>

	<div>

		<!-- dollars -->
		<div id="conteneurR">
			<div class="elementR"> 
				<p>
					USD - Dollars:
				</p>
			</div>
			<div class="elementR">
				<p class="center"> 
					<?php echo $_GET["USD"];?>
				</p>
			</div>
			<div class="elementR">
				<p class="center"> 
					<?php echo date("Y-m-d");?>
				</p>
			</div>
		</div>
		<!-- GBP -->
		<div id="conteneurR">
			<div class="elementR"> 
				<p>
					GBP - British Pound:
				</p>
			</div>
			<div class="elementR">
				<p class="center"> 
					<?php echo $_GET["GBP"];?>
				</p>
			</div>
			<div class="elementR">
				<p class="center"> 
					<?php echo date("Y-m-d");?>
				</p>
			</div>
		</div>
	</div>

	<p>
		<input class="button bottomButton" type="button" value="Update" onclick="window.location.href='do.update.php'"/>
	</p>

	<?php }?>

</body>

</html>

<?php
// -------------------- FUNCTIONS --------------------

// ----- Treatment -----
// parameterControl
function parameterControl(){
	if (isset($_GET["USD"]) AND isset($_GET["GBP"]) ) {
		global $rateUSD, $rateGBP;
		$rateUSD = $_GET["USD"];
		$rateGBP = $_GET["GBP"];
		return true;
	}
	else {
		echo '<p class="err">Incorrect url parameters</p>';
		return false;
	}	
}

?>