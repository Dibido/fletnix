<?php
	// PDOscript voor de databasefuncties
connectdb($username = "wtadmin", $password = "P@ssw0rd"){
	$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", $username, $password) or die("Er kon geen database connectie gemaakt worden.");
}
?>