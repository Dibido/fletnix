<!-- TOS high-fidelity -->
<?php
session_start();
	//database connectie opzetten
$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");
	//result initieren
$result = array();

if($_SERVER['REQUEST_METHOD'] === 'GET'){
	header("Location: zoeken.php");
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	if ($_SESSION['loggedin'] == false || $_SESSION['active'] == false){
		header("Location: inloggen.php");
	}
	
	if(!empty($_POST['simplesearch'])){
		$search = $_POST['simplesearch'];
			//bouwen query
		$sql = "SELECT * FROM Movie m WHERE m.title LIKE '%".$search."%' ORDER BY m.cover_image DESC;";
			//afschieten query
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}
		
		//advanced search queries
	if(!empty($_POST['director'])){
		$search = explode(" ", $_POST['director']);
		$firstname = $search[0];
		$lastname = $search[1];
		$sql ="SELECT DISTINCT TOP 20 * FROM Movie WHERE movie_id IN ( SELECT m.movie_id FROM Movie_Director d JOIN Person p ON d.person_id = p.person_id JOIN Movie m ON m.movie_id = d.movie_id WHERE p.firstname LIKE '%".$firstname."%' AND p.lastname LIKE '%".$lastname."%') ORDER BY m.cover_image DESC;";
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}
		
	if(!empty($_POST['actor'])){
		$search = explode(" ", $_POST['actor']);
		$firstname = $search[0];
		$lastname = $search[1];
		$sql ="SELECT DISTINCT TOP 20 * FROM Movie WHERE movie_id IN ( SELECT m.movie_id FROM Movie_Cast c JOIN Person p ON c.person_id = c.person_id JOIN Movie m ON m.movie_id = c.movie_id WHERE p.firstname LIKE '%".$firstname."%' AND p.lastname LIKE '%".$lastname."%') ORDER BY m.cover_image DESC;";		
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	if(!empty($_POST['year']) && strlen($_POST['year']) == 4){
		$search = $_POST['year'];
		$sql ="SELECT DISTINCT TOP 20 * FROM Movie m WHERE publication_year = ".$search." ORDER BY m.cover_image DESC;";
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	if(!empty($_POST['advancedsearch'])){
		$search = $_POST['advancedsearch'];
		$sql = "SELECT DISTINCT * FROM Movie m WHERE m.title LIKE '%".$search."%' ORDER BY m.cover_image DESC;";
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	if(!empty($_POST['genres'])){
		$genres = $_POST['genres'];
		$formattedgenres = "";
		$formattedgenres = "'".$formattedgenres.implode("', '", $genres)."'";
		$sql ="SELECT DISTINCT TOP 20 m.* FROM Movie m JOIN Movie_Genre g ON m.movie_id = g.movie_id WHERE g.genre_name IN (".$formattedgenres.") ORDER BY m.cover_image DESC;";
		$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	if(!empty($_POST['director']) && !empty($_POST['actor']) && !empty($POST['year']) && !empty($POST['advancedsearch']) && !empty($POST['genres'])){
			//Waardes uit de POST halen en in variabelen zetten
		$sql ="SELECT DISTINCT TOP 20 m.* FROM Movie m JOIN Person p ON Movie_Genre g ON m.movie_id = g.movie_id JOIN Movie_Director d ON m.movie_id = d.movie_id JOIN Cast c ON m.movie_id = c.movie_id WHERE ######### ORDER BY m.cover_image DESC;";
	}

	
	function printresults(){
		global $pdo;
		global $result;
		global $genres;
			//checken of er films gevonden zijn
		if(!isset($result[0])){
			die("<h2> Geen films gevonden die voldoen aan deze criteria. </h2>");
		}
			//beginnen tabel
		echo "<table>";
			//tabel headers maken
		echo "<tr>";
		echo "<th>Cover</th>";
		echo "<th>Titel</th>";
		echo "<th>Jaar</th>";
		echo "<th>Lengte</th>";
		echo "<th>Prijs</th>";
		echo "<th>Genre(s)</th>";
		echo "<th>Director(s)</th>";
		echo "</tr>";
		foreach($result as $row){
				//ophalen film genres
			$sql = "SELECT genre_name FROM Movie m JOIN movie_genre g on m.movie_id = g.movie_id where m.movie_id = ".$row['movie_id'].";";
			$filmgenres = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
				//formatteren genres
			if($filmgenres){
				$formattedgenres = "";
				foreach($filmgenres as $genre){
					$formattedgenres = $formattedgenres.implode(" ", $genre).", ";
				}
			$formattedgenres = rtrim($formattedgenres, ", ");
			}
				//ophalen director(s)
			$sql = "SELECT p.firstname, p.lastname FROM Movie m JOIN Movie_Director d ON m.movie_id = d.movie_id JOIN Person p ON d.person_id = p.person_id WHERE d.movie_id = ".$row['movie_id']." ORDER BY p.firstname ASC;";
			$filmdirectors = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
				//directors array formatten
			if($filmdirectors){
			$formatteddirectors = "";
			foreach($filmdirectors as $director){
				$formatteddirectors = $formatteddirectors.implode(" ", $director).", ";
				}
			$formatteddirectors = rtrim($formatteddirectors, ", ");
			}
				//als er geen plaatje is placeholder gebruiken
			if(!isset($row['cover_image'])){
				$row['cover_image'] = "placeholder.png";
			}
				//filmlengte berekenen
			$filmlengte = date('G\u\u\r i\M\i\n', mktime(0,$row['duration']));
				//rijen invullen
			echo "<tr>";
			echo "<td><a href='film.php?movie_id=".$movie_id=$row['movie_id']."'> <img src='data/image_data/".$row['cover_image']."' width='40' height='60'></td>";
			echo "<td>".$row['title']."</td>";
			echo "<td>(".$row['publication_year'].")</td>";
			echo "<td>".$filmlengte."</td>";
			echo "<td>".$row['price']."</td>";
			if(isset($formattedgenres)){
			echo "<td>".$formattedgenres."</td>";
			}
			if(isset($formatteddirectors)){
			echo "<td>".$formatteddirectors."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
}


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Fletnix Nederland - TOS</title>
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
	<?php printresults(); ?>
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


