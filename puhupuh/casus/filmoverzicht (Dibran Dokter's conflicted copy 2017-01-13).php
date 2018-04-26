<!-- Filmoverzichtpagina dynamisch -->

<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if ($_SESSION['loggedin'] == false || $_SESSION['active'] == false){
		header("Location: inloggen.php");
	}
	
	function favorieten(){
			//maak een lijst met favorieten van de gebruiker.
	}
	
	function top10films(){
			//pdo object klaarmaken
		$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");
			//top 10 films ophalen
			//sql query bouwen
		$sql = "SELECT TOP 10 movie_id, cover_image, title, publication_year FROM Movie WHERE cover_image IS NOT NULL";
			//query draaien
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) or die(print_r($pdo->errorInfo(), true));
			//rijen met images en titels maken en een link naar de pagina met movie_id
			//lijst beginne
		echo '<p class="lijstheaders"><strong>Top 10</strong></p>';
		echo '<ul class="covers-container">';
			//plaatjes en titels laden
		foreach($result as $row){
				//als er geen plaatje is placeholder gebruiken
			if(!isset($row['cover_image'])){
				$row['cover_image'] = "placeholder.png";
			}
			echo "<li class='cover'><a href='film.php?movie_id=".$row['movie_id']."'><img src='data/image_data/".$row['cover_image']."'height='180' width='130' alt='data/image_data/placeholder.png'></a><p>".$row['title']." (".$row['publication_year'].")</p></li>\n";
		}
			//lijst afsluiten
		echo "</ul>";
	}
	
	function meestrecentereleases(){
			//pdo object klaarmaken
		$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");
			//meest recente releases ophalen
			//sql query bouwen
		$sql = "SELECT TOP 10 movie_id, cover_image, title, publication_year FROM Movie ORDER BY publication_year DESC";
			//query draaien
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) or die(print_r($pdo->errorInfo(), true));
			//rijen met images en titels maken en een link naar de pagina met movie_id
			//lijst beginnen
		echo "<p class='lijstheaders'><strong>Nieuwe Releases</strong></p>";
		echo "<ul class='covers-container'>";
		foreach($result as $row){
			if(!isset($row['cover_image'])){
				$row['cover_image'] = "placeholder.png";
			}
			echo "<li class='cover'><a href='film.php?movie_id=".$row['movie_id']."'><img src='data/image_data/".$row['cover_image']."'height='180' width='130' alt='data/image_data/placeholder.png'></a><p>".$row['title']." (".$row['publication_year'].")</p></li>\n";
		}
			//lijst afsluiten
			echo "</ul>";
	}
	
	function populairstefilms(){
		//pdo object klaarmaken
		$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");
			//meest recente releases ophalen
			//sql query bouwen
		$sql = "SELECT movie_id, cover_image, title, publication_year FROM movie WHERE movie_id IN ( SELECT TOP 5 movie_id from watchhistory group by movie_id order by COUNT(movie_id) DESC );";
			//query draaien
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) or die(print_r($pdo->errorInfo(), true));
			//rijen met images en titels maken en een link naar de pagina met movie_id
			//lijst beginnen
		echo "<p class='lijstheaders'><strong>Populair</strong></p>";
		echo "<ul class='covers-container'>";
		foreach($result as $row){
			if(!isset($row['cover_image'])){
				$row['cover_image'] = "placeholder.png";
			}
			echo "<li class='cover'><a href='film.php?movie_id=".$row['movie_id']."'><img src='data/image_data/".$row['cover_image']."'height='180' width='130' alt='data/image_data/placeholder.png'></a><p>".$row['title']." (".$row['publication_year'].")</p></li>\n";
		}
			//lijst afsluiten
			echo "</ul>";
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
}


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Fletnix Nederland - Filmoverzicht</title>
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
					<li><a  class="active" href="overzicht.html">Filmoverzicht</a></li>
					<li><a href="account.html">Account</a></li>
					<li><a href="contact.html">Contact</a>
						<ul>
							<li class="sub-menu-item"><a href="contact.html">Over Ons</a></li>
							<li class="sub-menu-item"><a href="TOS.html">Terms of Service</a></li>
						</ul>
					</li>
					</ul>
				</div>
			</nav>
		</div>
</header>

<div class="content">
	<form action ='zoeken.php' method='post'>
		<br><input type="search" id="overzichtzoekbalk" name="moviesearch" placeholder="zoeken">
	</form>
		<?php 
		top10films();
		echo "<br><br><br><br>";
		meestrecentereleases(); 
		echo "<br><br><br>";
		populairstefilms();
		?>
	<p class="lijstheaders"><strong>Populair</strong></p>
	<ul class="covers-container">

	</ul>
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
