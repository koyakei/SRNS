<html>
<head>
<title>Tag Resister</title>
</head>
<body>
<php?
$tagAdd = $_REQUEST['tagAdd']; //タグの関連付けを追加する
$sql = "SELECT * FROM  `Tag` WHERE  `name` LIKE  '$tagAdd'";
$tagIDfinding = $pdo->query($sql);
$i = 0;
while ($row = $tagIDfinding->fetch()) {
	$name = htmlspecialchars($row['name']);
	$ID = htmlspecialchars($row['ID']);
	$ownerID = htmlspecialchars($row['ownerID']);
	$ownerProfile = htmlspecialchars($row['ownerProfile']);
	$Tag = array(
		'name' => $name,
		'ID' => $ID,
		'name' => $ownerID,
		'name' => $ownerProfile,
	);
	$tagA[$i] = $Tag;
	$i++;
}
foreach ($tagA as $eachInfo){

}
$AllRequest = $_REQUEST;
echo "<form action='result.php' method='post'>";
echo "<input name='allRequest' value='$_REQUEST' type='hidden' />";
echo "</form>";
?>
</body>
</html>