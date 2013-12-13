<html>
<head>
<title>Top</title>
</head>
<br>
<table border="1">
<?php
include("cmn/debug.php");
require_once("cmn/utils.php");
  $pdo = db_open();
  $st = $pdo->query("SELECT * FROM `Tag` WHERE `ID` =1");
  while ($row = $st->fetch()) {
    $id = htmlspecialchars($row['ID']);
    $name = htmlspecialchars($row['name']);
    echo "<tr><td>$id</td><td>$name </td></tr>";
  }
$pdo = null;
?>
</table>
<form action="search.php" method="post">
AND  <input type="text" name="andSearch1"> <input type="text" name="andSearch2"><br>
OR  <input type="text" name="orSearch"><br>
NOT  <input type="text" name="notSearch"><br>
<input value="検索" type="submit" name="Search">
</form>

</html>