<html>
<head>
<link rel="stylesheet" type="text/css" href="css/tablesorter.css" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="js/jquery-2.0.3.js"></script>
<script type="text/javascript">
<!--

function editMainTag() {
  document.getElementById("editMainTag").style.visibility = "true";
}
function changeMainTag() {
  alert('called changeMainTag');
}
function submitMainTag() {
  alert('called submitMainTag');
}
//-->
</script>

<!--ダウンロードしたファイル-->
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="js/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript" src="js/docs/js/docs.js"></script> 
<!--javascript追記-->
<script type="text/javascript">
	$(function() {
		$("table")
			.tablesorter({widthFixed: true, widgets: ['zebra']})
			.tablesorterPager({container: $("#pager")});
	});
</script>



<title>Top</title>
</head>
<body>
<br>

<table border="1" class="tablesorter">
<thead>
	<tr>
		<th>
			tag
		</th>
		<th>
			2
		</th>
</thead>
<?php
ini_set( 'display_errors', 1 );
require_once("cmn/debug.php");
require_once("cmn/utils.php");
  $pdo = db_open();
$tagID = $_REQUEST['ID'];
$tagEdit = htmlspecialchars($_POST['tagEdit']);
if ($tagEdit != null) {
$pdo->beginTransaction();
 $sql = "UPDATE `db0tagplus`.`Tag` SET `name` = '$tagEdit' WHERE `Tag`.`ID` = $tagID;";
$pdo->exec($sql); $pdo->commit();
  $sql = "SELECT '$tagEdit' as name, '$tagID' as ID FROM `Tag` WHERE `ID` =$tagID";
} else {
  $sql = "SELECT * FROM `Tag` WHERE `ID` =$tagID";
}
$tagG = $pdo->query($sql);
while ($row = $tagG->fetch()) {
	$name = htmlspecialchars($row['name']);
	$ID = htmlspecialchars($row['ID']);
	echo "<form action='result.php' method='post'>";
	echo "<tr><td><a href='result.php?ID=$ID' target='_blank'>$name</a><div id='viewMainTag' onClick='editMainTag();' ><input value='編集' type='submit' name='Edit'></div><div id='editMainTag'><input name='tagEdit' value='$name' style='visible: hidden;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div><input name='ID' value='$tagID' type='hidden' />";
	echo "</form></td></tr>";
  }
?>
</table>
<table border="1">
<?php
$sql = "SELECT * FROM  `LINK` WHERE  `LFrom` = $tagID   LIMIT 0 , 30";
	$st = $pdo->query($sql);
while ($row = $st->fetch()) {
		$LTo = htmlspecialchars($row['LTo']);
		$sql = "SELECT * FROM  `article` WHERE  `ID` = $LTo  LIMIT 0 , 30";
		$articleDisplay = $pdo->query($sql);
				while ($row = $articleDisplay->fetch()) {
				$name = htmlspecialchars($row['name']);
				$ID = htmlspecialchars($row['ID']);
				echo "<tr>";
				echo "<td><a href='result.php?ID=$ID' target='_blank'>$name</a></td><td></td>";
				$sql = "SELECT * FROM  `Tag` WHERE  `ID` = $ID  LIMIT 0 , 30";
				$TagD = $pdo->query($sql);
					while ($row = $articleD->fetch()) {
					}
				echo "</tr>";
				}
	}
?>
</table>
</html>





