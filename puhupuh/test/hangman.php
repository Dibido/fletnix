<?php
	session_start(); //associative array $_SESSION
	$rword = "";
	
if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$_SESSION['aantal'] = 0;
	//bestand inlezen
	$fd = fopen("words.txt", "r") or exit();
	$words = array();
	while($word = fgets($fd)){
		$words[] = trim($word);
	}
	fclose($fd);
	//array opslaan in sessie
	$_SESSION['words'] = $words;	
	//rand() om een index te kiezen
	$index = rand(0,count($words)-1);
	$sword = $words[$index];
	//sword opslaan
	$_SESSION['sword'] = $sword;
	//echo array om te checken
}
if($_POST){
	$gword = $_POST['gword'];
	$sword = $_SESSION['sword'];
	$words = $_SESSION['words'];
	if(strlen($gword) != strlen($sword)){
		if(strlen($gword) == 0){
			$rword = "invoeren";
		}
		else{
			$rword = "lengte";
		}
	}
	if($gword == $_SESSION['gword']){
		
	}
	else{
	if($gword == $sword){
		$rword = "OK";
		$_SESSION['aantal'] = 0;
		$index = rand(0,count($words)-1);
		$sword = $words[$index];
		$_SESSION['old'] = $gword;
	}
	else{
		$rword = comparewords($gword, $sword);
		if ($_SESSION['old'] != $gword){
		$_SESSION['aantal']++;
		}
		$_SESSION['old'] = $gword;
	}
	}
}

function comparewords($sword, $gword){
	$rword = "";
		for ($i=0; $i < strlen($sword); $i++){
			if($sword[$i] == $gword[$i]){
				$rword = $rword.$sword[$i];
			}
			else{
				$rword = $rword.'.';
			}
		}
	return $rword;
}
?>

<!DOCTYPE HTML>
<head> </head>
<style>
table{border-collapse:collapse;border-spacing:0;}
table td {bord: 1px solid black}
</style>
<body>
<table>
<tr>
<tr colspan="2">Lengte van het woord: <?php echo strlen($sword);?></tr></tr>
<tr>
<td><p>Poging:</p></td>
<td><p>Result:</p></td>
<td><p>Aantal pogingen:</p></td>
</tr>
<form action="#" method="post">
<td><input type=text name=gword></td>
<td><input type=text name=sword disabled value="<?php echo $rword;?>"></td>
<td><input type=text style="width: 20px;" disabled value=<?php echo $_SESSION['aantal']; ?>></td>
<td><input type=submit></td>
</table>
</body>