<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$_SESSION['loggedon'] = false;
	$_SESSION['user'] = "";
	$_SESSION['views'] = 0;
}
else
{
	switch($_POST['action'])
	{
		case 'logon':	
						$user = $_POST['user'];
						$password = $_POST['password'];
						$message = "";
						//$pdo = new PDO("mysql:host=localhost;dbname=logon", "root", "");
						$pdo = new PDO("sqlsrv:server=localhost;database=logon;ConnectionPooling=0", "wtadmin", "P@ssw0rd");
						$sql = "SELECT * FROM USERS WHERE USERNAME ='".$user."' AND PASSWORD='".md5($password)."'";
						echo $sql;
						$result = $pdo->query($sql);
						if($result->rowcount())
						{
							$_SESSION['loggedon']=true;
							$_SESSION['user']=$_POST['user'];
							$_SESSION['views']=1;
						}
						break;
		case 'refresh':
						$_SESSION['views']++;
						break;
		case 'logoff':
						$_SESSION['loggedon']=false;
						$_SESSION['user'] = "";
						$_SESSION['views'] = 0;
						break;
		case 'insert':
						$user = $_POST['user'];
						$password = $_POST['password'];
						$message = "";
						$pdo = new PDO("sqlsrv:server=localhost;database=logon;ConnectionPooling=0", "wtadmin", "P@ssw0rd");
						$sql = "INSERT INTO USERS VALUES('".$user."','". md5($password)."')";
						//echo $sql;
						$result = $pdo->query($sql);
						if($result->rowcount() != 1){
							$message = "could not insert";
							echo $message;
						}
						break;
		default:		break;
	}
}

if($_SESSION['loggedon'] == false){
	require('forma.php');
}
else
{
	require('formb.php');
}
?>