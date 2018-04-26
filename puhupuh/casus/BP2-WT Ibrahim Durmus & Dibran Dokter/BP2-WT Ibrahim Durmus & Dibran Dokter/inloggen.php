<!-- Inlogpagina Dynamisch -->
<?php
session_start();
	//Session array legen
$_SESSION =  array();

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//data checken
	if(strlen($_POST['password']) < 8){
		die("password te kort.");
		if(strlen($_POST['password']) > 28){
			die("password te lang.");
		}
	}
	else{
		//setten variabelen
	$email = $_POST['email'];
	$password = $_POST['password'];
	
		//hashen wachtwoord
	$password = hash("sha256", $password);
	
		//opzetten databaseconnectie met errorchecking dmv die()
	$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");
	
	$sql = "SELECT * FROM Customer WHERE customer_mail_address = '".$email."' AND password = '".$password."';";
	
	$result = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC) or die("We kunnen geen account met dit e-mailadres vinden. <a href='inloggen.php'>Probeer het opnieuw</a> of <a href='abonnementen.html'>maak een nieuw account.</a>");
	
			//checken of het account geactiveert is
		if($result['active'] != 1){
			die("Account is niet geactiveert.");
		}
			//checken of de data correct is.
		if ($result['customer_mail_address'] === $email && $result['password'] === $password) {
				//database informatie in de sessie inladen
			$_SESSION = $result;
			$_SESSION['loggedin'] = true;
				//naar filmoverzicht gaan
			header("Location: filmoverzicht.php");
		}
		else{
			die("could not find account, ".$pdo->errorInfo()."\n");
		}
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Fletnix Nederland - Inloggen</title>
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

<header>
	<div id="menu">
			<nav>
		<div id="menulist">
				<div id="logo">
					<a href="index.html"><img src="img/logo.png"  alt="Fletnix logo"></a>
				</div>
					<ul id="nav">
					<li><a href="index.html">Home</a></li>
					<li><a href="filmoverzicht.php">Filmoverzicht</a></li>
					<li><a href="contact.html">Contact</a>
						<ul>
							<li class="sub-menu-item"><a href="contact.html">Over Ons</a></li>
							<li class="sub-menu-item"><a href="TOS.html">Terms of Service</a></li>
						</ul>
					</li>
					<li><a class="active" href="inloggen.html">Inloggen</a></li>
					</ul>
				</div>
			</nav>
		</div>
</header>




<div class="content">
	<div class="inlogform">
		<form id='loginform' action='#' method='post' accept-charset='UTF-8'>
			<fieldset >
				<legend>Log in</legend>
				<label for='email' >Email:</label><br>
				<input type='email' name='email' id='email' required /><br>
				<label for='password' >Wachtwoord :</label><br>
				<input type='password' name='password' id='password' pattern=".{8,}" required title="Langer dan 6 karakters."/><br>
				<input id="submit" type='submit' name='Submit' value='Login' />
				<p>Nog geen account? <a href="abonnementen.html">Registreer hier!</a></p>
			</fieldset>
		</form>
	</div>
</div>

<footer class="footer">

	<div class="footer-rechts">
		<a href="#"><i class="fa fa-facebook"></i></a>
		<a href="#"><i class="fa fa-twitter"></i></a>
		<a href="#"><i class="fa fa-linkedin"></i></a>
		<a href="#"><i class="fa fa-github"></i></a>
	</div>

	<div class="footer-links">
		<p class="footer-lijst">
			<a href="index.html">Home</a> ·
			<a href="abonnementen.html">Abonnementen</a> ·
			<a href="contact.html">Contact</a> ·
			<a href="TOS.html">TOS</a>
		</p>
		<p>Vragen? Bel naar <a href="tel:+318000229647">0800-022-9647</a></p>
	</div>
</footer>


</body>
</html>