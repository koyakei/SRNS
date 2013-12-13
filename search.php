<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Top</title>
</head>
<body>
<br>
<table border="1">
<?php
require_once("cmn/debug.php");
ini_set( 'display_errors', 1 );
require_once("cmn/utils.php");
  $pdo = db_open();
$search1 = $_POST['andSearch1'];
$search2 = $_POST['andSearch2'];
if ($search2 == null) {
  $sql = "SELECT * FROM  `Tag` WHERE  `name` LIKE  '%$search1%' LIMIT 0 , 30";
} else {
  $sql = "SELECT * FROM  `Tag` WHERE  `name` LIKE  '%$search1%'  AND `name` LIKE  '%$search2%' LIMIT 0 , 30";
}
//タグを検索
$st = $pdo->query($sql);
  while ($row = $st->fetch()) {
	$name = htmlspecialchars($row['name']);
	$tagID = htmlspecialchars($row['ID']);
	echo "<tr><td><a href='result.php?tagID=$tagID' target=’_blank’>$name</a></td></tr>";
  }
$pdo = null;
?>

</table>
</body>
</html>