<!DOCTYPE html>

<html>
<head>
    <title>Grootste Nederlandse Steden</title>
	<meta charset="utf-8">
</head>
<body>
	
	<?php
		$pdo = new PDO("mysql:host=localhost;dbname=world", "root", "");
		
		$sql = 'SELECT * FROM COUNTRY WHERE LifeExpectancy < 50 ORDER BY LifeExpectancy ASC LIMIT 30';
		// echo $sql;
		
		$result = $pdo->query($sql);
	
		echo '<table border="1">';
		echo '<tr>';
		echo '<th>ID</th><th>Name</th><th>CountryCode</th><th>District</th><th>Population</th>';
		echo '</tr>';
		
		while ($row = $result->fetch(PDO::FETCH_NUM))
		{
			echo '<tr>';
			foreach ($row as $value)
			{
				echo '<td>';
				$value = str_replace("´", "&#180;", $value);
				echo $value;
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	?>		
</body>
</html>
