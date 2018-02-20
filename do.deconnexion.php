<!-- do.deconnection.php -->

<?php require "session.php" ?>

<?php
    session_destroy();
    header("Location: connection.php?error=deconnect");
	exit();
?>
