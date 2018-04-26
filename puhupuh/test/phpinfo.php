<?php

//phpinfo();

error_reporting(E_ALL);
ini_set('display_errors','On');
echo '<pre>';
print_r(PDO::getAvailableDrivers());
echo '</pre>';

$pdo = new PDO("sqlsrv:server=localhost;database=logon;ConnectionPooling=0", "wtadmin", "P@ssw0rd");
$stmt = $pdo->prepare("SELECT * FROM logon.dbo.users");
$stmt->execute();
$data=$stmt->fetchAll(PDO::FETCH_COLUMN,0);
print_r($data);
?>
