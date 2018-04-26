<!-- film.php dynamisch-->
<?php
session_start();
	//database connectie opzetten
$pdo = new PDO("sqlsrv:server=localhost;database=WTdb;ConnectionPooling=0", "wtadmin", "P@ssw0rd") or die("Er kon geen database connectie gemaakt worden.");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	if(isset($_POST['favoriet'])){
		global $pdo;
		global $movie_id;
		$favoriet = $_SESSION['favoriet'];
			//zet film als favoriet of niet
			//maken query
		if($favoriet === false){
			$sql = "INSERT INTO Favorite VALUES('".$_SESSION['customer_mail_address']."', ".$_SESSION['movie_id'].");";
		}
		else{
			$sql = "DELETE FROM Favorite WHERE customer_mail_address = '".$_SESSION['customer_mail_address']."' AND movie_id = ".$_SESSION['movie_id'].";";	
		}
			//query draaien
		$pdo->query($sql) or die(print_r($pdo->errorInfo()));
			//verander $favoriet boolean
		!$favoriet;
		
		header("Location: film.php?movie_id=".$_SESSION['movie_id']."");
	}
}	

if($_SERVER['REQUEST_METHOD'] === 'GET'){
	if ($_SESSION['loggedin'] == false || $_SESSION['active'] == false){
		header("Location: inloggen.php");
	}
	
	$_SESSION['movie_id'] = $_GET['movie_id'];
	$movie_id = $_SESSION['movie_id'];	
		//alle moviedata ophalen
		//query opbouwen
	$sql = "SELECT * FROM Movie WHERE movie_id = ".$movie_id.";"; 
		//query afschieten
	$movie = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) or die(print_r($pdo->errorInfo(), true));
	//functie voor titel aanpassen
	function paginatitel(){
		global $movie;
		echo $movie[0]['title'];
	}
	//checken of film een favoriet is
	$sql = "SELECT * FROM Favorite WHERE customer_mail_address='".$_SESSION['customer_mail_address']."' AND movie_id = ".$movie_id.";";
	$favorietcheck = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	
	if(!$favorietcheck){
		$favoriet = false;
	}
	else{
		$favoriet = true;
	}
	
	$_SESSION['favoriet'] = $favoriet;
	
	if($favoriet){
		$favoriettext = "Unfavorite";
	}
	else{
		$favoriettext = "Favorite";
	}
}

	//functie voor inladen filmdata en knop voor het afspelen
function filmdata(){
	global $pdo;
	global $movie;
	global $movie_id;
	global $favoriettext;
		//ophalen genres
	$sql = "SELECT genre_name FROM Movie m JOIN movie_genre g on m.movie_id = g.movie_id where m.movie_id = ".$movie_id.";";
	$genres = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) or die(print_r($pdo->errorInfo(), true));
		//ophalen directors
	$sql = "SELECT p.firstname, p.lastname FROM Movie m JOIN Movie_Director d ON m.movie_id = d.movie_id JOIN Person p ON d.person_id = p.person_id WHERE d.movie_id = ".$movie_id." ORDER BY p.firstname ASC;";
	$directors = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		//ophalen actors
	$sql = "SELECT p.firstname, p.lastname FROM Movie m JOIN Movie_Cast c ON m.movie_id = c.movie_id JOIN Person p ON c.person_id = p.person_id WHERE c.movie_id = ".$movie_id." ORDER BY p.firstname ASC;";
	$actors = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		//lengte van film in uren berekenen
	$filmlengte = date('G\u\u\r i\M\i\n', mktime(0,$movie[0]['duration']));
		//cover_image vullen
	if(!isset($movie[0]['cover_image'])){
		$movie[0]['cover_image'] = "placeholder.png";
	}
		//trailer URL vullen
	if(!isset($movie[0]['URL'])){
		$movie[0]['URL'] = 'https://www.youtube.com/embed/ponurLICAoM';
	}
		//genres formatten
	if($genres){
		$formattedgenres = "";
		foreach($genres as $genre){
			$formattedgenres = $formattedgenres.implode(" ", $genre).", ";
		}
		$formattedgenres = rtrim($formattedgenres, ", ");
	}
		//directors array formatten
	if($directors){
		$formatteddirectors = "";
		foreach($directors as $director){
			$formatteddirectors = $formatteddirectors.implode(" ", $director).", ";
		}
		$formatteddirectors = rtrim($formatteddirectors, ", ");
	}
		//actors array formatten
	if($actors){
		$formattedactors = "";
		foreach($actors as $actor){
			$formattedactors = $formattedactors.implode(" ", $actor).", ";
		}
		$formattedactors = rtrim($formattedactors, ", ");
	}
		//printen waardes
	echo '<div class="movie-flex">';
	echo '<h1>'.$movie[0]['title'].'</h1>';
	echo '<h3>('.$movie[0]['publication_year'].')</h3>';
	echo '<br>';
	echo '<form action="#" method="post"><input class="button" id="submit" type="submit" name="favoriet" width="20" height="10" value="'.$favoriettext.'"/></form>';
	echo '</div>';
	echo '<p> '.$filmlengte.' | '.$formattedgenres.' | '.$movie[0]['publication_year'].'</p>';
	echo '<div class="movie-flex">';
	echo '<a href="data/image_data/'.$movie[0]['cover_image'].'"><img src="data/image_data/'.$movie[0]['cover_image'].'" height="200" width="150" alt="The Matrix"></a>';
	echo '<div class="movieinformation-flex">';
	echo '<p id="description">'.$movie[0]['description'].'</p>';
	if(isset($formatteddirectors)){
		echo '<p>Directors: '.$formatteddirectors.'.</p>';
	}
	if(isset($formattedactors)){
		echo '<p>Actors: '.$formattedactors.'.</p>';
	}
	echo '<button class="button" style="width:100px;height:50px"><a style="text-decoration=none;" href="afspelen.php?movie_id='.$movie_id.'">Afspelen</a></button>';
	echo '</div>';
	echo '</div>';
	echo '<div id="trailer">';
	echo '<iframe width="420" height="315" src="'.$movie[0]['URL'].'"></iframe>';
	echo '</div>';
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
	<?php
	filmdata();
	?>
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