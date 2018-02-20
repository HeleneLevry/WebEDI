<!-- session.php -->

<?php
	session_cache_limiter('private');
    session_cache_expire(30);
    session_start();
    $expire_time = 30*60; // 30 min sans actiité => expiration de la session
?>