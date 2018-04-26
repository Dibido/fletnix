<!-- Zoeken Dynamisch -->
<?php
session_start();
	//database connectie opzetten
$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");

if($_SERVER['REQUEST_METHOD'] === 'GET'){
	if ($_SESSION['loggedin'] == false || $_SESSION['active'] == false){
		header("Location: inloggen.php");
	}
		//functie die alle genreopties een voor een echoed
	function genreopties(){
		global $pdo;
			//maken sql query
		$sql = "SELECT DISTINCT genre_name FROM Genre";
			//afschieten query
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($result as $row){
			foreach($row as $genre){
				echo '<option value='.$genre.'>'.$genre.'</option>';
			}
		}
	}
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Fletnix Nederland - Zoeken</title>
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
					<li><a class="active" href="filmoverzicht.php">Filmoverzicht</a></li>
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
<h1>Vind de beste films en series van <?php echo date("Y");?> op FletNix!</h1>

<fieldset>
	<form action='resultaten.php' method='post'>
		<legend>Zoeken</legend>
		<br>
		<legend>Titel:</legend>
		<input type='search' name='simplesearch' placeholder='Zoeken' pattern=".{4,}" title="Langer dan 4 karakters"/>
		<button type="submit">Zoeken</button><br>
	</form>
	<label for="geavanceerd_zoeken" class="btnzoek">Geavanceerde opties</label>
			<input type="checkbox" id="geavanceerd_zoeken" role="button">
	<div id="geavanceerd_div"> 
		<br>
		<form id='zoekform' action='resultaten.php' method='post'>
		<legend>Regisseur:</legend>
		<input type='search' name='director' placeholder='voornaam achternaam'/>
		<legend>Acteur:</legend>
		<input type='search' name='actor' placeholder='voornaam achternaam'/>
		<legend>Releasejaar:</legend>
		<input type='search' name='year' pattern="[0-9]{4,4}" title="Max 4 cijfers" min='1890' max='<?php echo date("Y");?>' placeholder='Jaar'><br>
		<legend>Titel:</legend>
		<input type='search' name='advancedsearch' placeholder='Titel'/>
		<legend>Genre(s): </legend>
		<select name="genres[]" multiple="multiple">
			<!-- voor elk genre in de Genre tabel een optie maken -->
			<?php genreopties(); ?>
		</select>
		<button type="submit">Zoeken</button>
		</form>
	</div>
</fieldset>

</div>	

<!-- FOOTER -->
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


