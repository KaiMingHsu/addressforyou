<html>
<head>
<title>雙重迴圈</title>

</head>
<body>
<table border="1">
<?php
echo "<tr><td>*</td>";
for($i=1;$i<=9;$i++)
	echo "<td><b>$i </b></td>";
echo "</tr>";
for( $i = 1;$i<=9;$i++){
	echo "<tr>";
	echo "<td><b>$i </b></td>";
	$j=1;
	while($j<=9){
		echo "<td>";
		echo "$i *$j = ".$i*$j;
		echo "</td>";
		$j++;
	}
	echo "</tr>";
}



?>

</table>
</body>

</html>