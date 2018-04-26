<!-- Afspeelpagina dynamisch -->
<?php
session_start();
	//database connectie opzetten
$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");

if($_SERVER['REQUEST_METHOD'] === 'GET'){
		//als gebruiker niet is ingelogd naar inlogpagina gaan
	if ($_SESSION['loggedin'] == false || $_SESSION['active'] == false){
			header("Location: inloggen.php");
		}
		//filmdata ophalen
	$_SESSION['movie_id'] = $_GET['movie_id'];
	$movie_id = $_SESSION['movie_id'];	
		//alle moviedata ophalen
		//query opbouwen
	$sql = "SELECT * FROM Movie m JOIN movie_genre g ON m.movie_id = g.movie_id WHERE m.movie_id = ".$movie_id.";"; 
		//query afschieten
	$movie = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) or die(print_r($pdo->errorInfo(), true));
		//watchhistory record aanmaken
	$watchdate = date("Y-m-d\TH:i:s");
	$sql = "INSERT INTO Watchhistory(movie_id, customer_mail_address, watch_date, price, invoiced) VALUES ('".$movie_id."', '".$_SESSION['customer_mail_address']."', '".$watchdate."', ".$movie[0]['price'].", 1);";
	$pdo->query($sql) or die(print_r($pdo->errorInfo(), true));
		//functie voor titel aanpassen
	function paginatitel(){
		global $movie;
		echo $movie[0]['title'];
	}
	
	function filminformatie(){
		global $pdo;
		global $movie;
		global $movie_id;
			//lengte van film in uren berekenen
		$filmlengte = date('G\u\u\r i\M\i\n', mktime(0,$movie[0]['duration']));
			//ophalen genres
		$sql = "SELECT genre_name FROM Movie m JOIN movie_genre g on m.movie_id = g.movie_id where m.movie_id = ".$movie_id.";";
		$genres = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) or die(print_r($pdo->errorInfo(), true));
			//genres formatten
		if($genres){
			$formattedgenres = "";
			foreach($genres as $genre){
				$formattedgenres = $formattedgenres.implode(" ", $genre).", ";
			}
			$formattedgenres = rtrim($formattedgenres, ", ");
		}
		echo '<div class="movie-flex">';
		echo '<h1>'.$movie[0]['title'].'</h1>';
		echo '<h3>('.$movie[0]['publication_year'].')</h3>';
		echo '</div>';
		echo '<p> '.$filmlengte.' | '.$formattedgenres.' | '.$movie[0]['publication_year'].'</p>';

	}
	
	function afspelen(){
		if(!isset($movie[0]['movie'])){
				$movie[0]['movie'] = "placeholder.ogg";
			}
		if(!isset($movie[0]['cover_image'])){
				$movie[0]['cover_image'] = "placeholder.png";
		}
		echo '<video autoplay src="data/movie_data/'.$movie[0]['movie'].'" controls poster="'.$movie[0]['cover_image'].'" width="320" height="240"></video>';
	}
}



?>

<!DOCTYPE html>
<html>
	<head>
		<title>Fletnix Nederland - Film - <?php paginatitel(); ?> </title>
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
	<div class="movie-flex">
			<div id="movie">
				<?php
				filminformatie();
				afspelen(); 
				?>
			</div>

</div>


</body>
</html>