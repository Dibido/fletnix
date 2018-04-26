<!-- activatiescript voor nieuwe accounts -->
<?php
session_start();

	//opzetten databaseconnectie met errorchecking dmv die()
$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
		//waardes in de sessie inladen
	$_SESSION['username'] = $_GET['user'];
	$_SESSION['key'] = $_GET['key'];
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!isset($_POST['submit'])){
			//als er niet op de knop is gedrukt naar de index gaan
		header("Location: index.html");
	}
		//variabelen uit de sessie inladen
	$username = $_SESSION['username'];
	$key =  $_SESSION['key'];
		//sql query bouwen
	$sql = "SELECT activation_key FROM Customer WHERE username = '".$username."';";
		//query draaien
	$result = $pdo->query($sql)->fetchColumn() or die ("Kan het account niet vinden, <a href='abonnementen.html'>registreer</a>.");
	
	if($result === $key){
			//juiste activeringscode
			//account actief zetten
		$sql = "UPDATE Customer SET active = 1 WHERE username ='".$username."';";
		$pdo->query($sql) or die ("Kon account niet actief zetten.");
			//succesvol actief gezet en naar loginpagina gaan
		header("Location: inloggen.php");
	}
	else{
		//onjuiste activeringscode
		die("Onjuiste activeringscode.");
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Fletnix Nederland - Activeren</title>
		<link href="img/favicon.png" rel="shortcut icon" type="image/x-icon"/>
		<link rel="stylesheet" type="text/css" href="opmaak.css">
		<!-- CSS voor icoontjes footer -->
		<link rel="stylesheet" href="fonts/css/font-awesome.min.css">
		<!-- Mobiel schalen en utf8 support -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div>
		<form id='activateform' action='#' method='post' accept-charset='UTF-8'>
			<input type='submit' name='submit' value='Activeer'/><br>
		</form>
	</div>
</body>