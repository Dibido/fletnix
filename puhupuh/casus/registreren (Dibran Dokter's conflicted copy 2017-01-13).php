<!-- Registratiepagina dynamisch -->
<?php
session_start();

date_default_timezone_set('UTC'); //tijdzone zetten voor de date() functie.

		//als de pagina met een get wordt opgehaald word het abbonnement_type die gekozen is opgeslagen.
if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(!$_GET['abbonnement']){
		header("Location: abonnementen.html");
	}
	$_SESSION['subscription_type'] = $_GET['abbonnement'];
	
	if($_SESSION['loggedin'] == true){
		header("Location: filmoverzicht.php");
	}
}
		//als de pagina met een post wordt opgehaald is er data ingevuld door de gebruiker. ???eerst checken en dan pas inladen???
if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//checken of de ingevulde data voldoet aan de databaseregels
	if(!isset($_SESSION['subscription_type'])){
		header("Location: abonnementen.html");
	}
	if(strlen($_POST['name']) < 8){
		die("Volledige naam te kort.");
		if(strlen($_POST['name']) > 28){
			die("Volledige naam te lang.");
	}
	}
	if(strlen($_POST['username']) < 8){
		die("Gebruikersnaam te kort.");
		if(strlen($_POST['username']) > 28){
			die("Gebruikersnaam te lang.");
		}
	}
	if(strlen($_POST['password']) < 8){
		die("password te kort.");
		if(strlen($_POST['password']) > 28){
			die("password te lang.");
		}
	}
	else{
			//waardes in variabelen stoppen
		$name = $_POST['name'];
		$email = $_POST['email'];
		$paypal = $_POST['paypal'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$subscription_begin = date("Ymd"); //huidige datum
		$d = new DateTime($subscription_begin);
		$d->modify( 'first day of next month' );
		$subscription_end = $d->format( 'Ymd' );
		$birthdate = $_POST['birthdate'];
			//key genereren aand de hand van de username (uniek)
		$key = sha1(rand(1, 99999) . $username);
			//hashen wachtwoord
		$password = hash("sha256", $password);

			//opzetten databaseconnectie met errorchecking dmv die()
		$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");
			//klaarmaken database query
		$sql = "INSERT INTO Customer (name, customer_mail_address, paypal_account, subscription_type, subscription_begin, subscription_end, username, password, birthdate, activation_key, country_name)
			VALUES('".$name."','".$email."','".$paypal."','".$_SESSION['subscription_type']."','".$subscription_begin."','".$subscription_end."','".$username."','".$password."','".$birthdate."','".$key."','Nederland');";
			//query uitvoeren en opslaan in $result
		$result = $pdo->query($sql) or die(print_r($pdo->errorInfo(), true));
			//checken of de insert gelukt is.
		if($result->rowcount() != 1){
			die("could not insert".$pdo->errorInfo()."\n");
		}
		else{
				//checken of account geactiveerd is
			
				//ga naar de activatiepagina
			header("Location: activeren.php?user=".$username."&key=".$key."");
		}
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Fletnix Nederland - Registeren</title>
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
					<li><a class="active" href="index.html">Home</a></li>
					<li><a href="overzicht.html">Filmoverzicht</a></li>
					<li><a href="contact.html">Contact</a>
						<ul>
							<li class="sub-menu-item"><a href="contact.html">Over Ons</a></li>
							<li class="sub-menu-item"><a href="TOS.html">Terms of Service</a></li>
						</ul>
					</li>
					<li><a href="inloggen.html">Inloggen</a></li>
					</ul>
				</div>
			</nav>
		</div>
</header>
<div class="content">
	<div class="form">
		<form id='registerform' action='#' method='post' accept-charset='UTF-8'>
			<fieldset >
				<legend>Registreer</legend><br>
				<label for='name' >Volledige naam:</label><br>
				<input type='text' name='name' id='name' pattern=".{8,28}" required title="Tussen 8 en 28 karakters." /><br>
				<label for='email' >Email:</label><br>
				<input type='email' name='email' id='email' required /><br>
				<label for='email' >Paypal:</label><br>
				<input type='email' name='paypal' id='paypal' required /><br>
				<label for='username' >Gebruikersnaam:</label><br>
				<input type='text' name='username' id='username' pattern=".{8,28}" required title="Tussen 8 en 28 karakters." /><br>
				<label for='password' >Wachtwoord:</label><br>
				<input type='password' name='password' id='password' pattern=".{6,28}" required title="Tussen 8 en 28 karakters."/><br>
				<label for='birthdate' >Geboortedatum:</label><br>
				<input type='date' name='birthdate' required min='1890-01-01' max='<?php echo date("Y-m-d"); ?>'><br>
				<input type='submit' name='Submit' value='Registreer'/><br>
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