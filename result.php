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
<form action='result.php' method='post'>
<?php
ini_set( 'display_errors', 1 );
require_once("cmn/debug.php");
require_once("cmn/utils.php");
  $pdo = db_open();
$tagID = $_REQUEST['tagID'];
$articleID = $_REQUEST['articleID'];
$tagEdit = htmlspecialchars($_POST['tagEdit']);
$articleEdit = htmlspecialchars($_POST['articleEdit']);
if ($articleEdit != null) {
$pdo->beginTransaction();
 $sql = "UPDATE `db0tagplus`.`article` SET `name` = '$articleEdit' WHERE `article`.`ID` = $articleID;";
$pdo->exec($sql); $pdo->commit();
};
if ($tagEdit != null) {
$pdo->beginTransaction();
 $sql = "UPDATE `db0tagplus`.`Tag` SET `name` = '$tagEdit' WHERE `Tag`.`ID` = $tagID;";
$pdo->exec($sql); $pdo->commit();
  $sql = "SELECT '$tagEdit' as name, '$tagID' as ID FROM `Tag` WHERE `ID` =$tagID";
} else {
  $sql = "SELECT * FROM `Tag` WHERE `ID` =$tagID";
}
$tagG = $pdo->query($sql);
$data = array();
$i = 0;
while ($row = $tagG->fetch()) {
	$name = htmlspecialchars($row['name']);
	$ID = htmlspecialchars($row['ID']);
	$searchingTag = array(
		'name' => $name,
		'ID' => $ID
	);
	$searchingTagA[$i] = $searchingTag;
	$i++;
};
?>
<?php
$sql = "SELECT  `article` . * FROM  `LINK` ,  `article` WHERE  `LINK`.`LFrom` =$tagID AND  `LINK`.`LTo` =  `article`.`ID` ";
	$articleSelect = $pdo->query($sql);
	while ($row = $articleSelect->fetch()) {
				$articleName = htmlspecialchars($row['name']);
				$articleID = htmlspecialchars($row['ID']);
				$article = array(
				'name' => $articleName,
				'ID' => $articleID
				);
			$articleA[$h] = $article;
				$sql = "SELECT `Tag` . * FROM  `LINK` ,  `Tag` WHERE  `LINK`.`LTo` =$article[ID] AND  `LINK`.`LFrom` = `Tag`.`ID` ";
				$articleD = $pdo->query($sql);
				while ($row = $articleD->fetch()) {
				$tagName = htmlspecialchars($row['name']); 
				$ID = htmlspecialchars($row['ID']);
				if ($tagList["$tagName"] == null) {
				$tagList["$tagName"] = array();
				}
     array_push($tagList["$tagName"], array( 'name' => $tagName,'ID' => $ID));
					};
				$h++;
	};

	
?>
<table border="1" class="tablesorter">
<?php
foreach ($searchingTagA as $searchingTag) {
	echo "<tr><td><a href='result.php?ID=$searchingTag[ID]' target='_blank'>$searchingTag[name]</a>
		<div id='viewMainTag' onClick='editMainTag();' ><input value='編集' type='submit' name='Edit'></div>
		<div id='editMainTag'><input name='tagEdit' value='$searchingTag[name]' style='visible: hidden;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div><input name='tagID' value='$searchingTag[ID]' type='hidden' /></td></tr>";
};
?>
</table>
<table border="1" class="tablesorter">

<?php
foreach ($tagList as $key => $value) {
	foreach ($value as $key2 => $record) {

		foreach ($articleA as $article) {
echo "<tr><td><a href='result.php?ID=$article[ID]' target='_blank'>$article[name]</a>
		<div id='viewMainTag' onClick='editArticle();' ><input value='編集' type='submit' name='Edit'></div>
		<div id='editMainTag'><input name='articleEdit' value='$article[name]' style='visible: hidden;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div><input name='articleID' value='$article[ID]' type='hidden' />$article[ID]</td>";
		echo "<td><a href='result.php?tagID=$record[ID]' target='_blank'>$record[name]</a><input name='tagID' value='$record[ID]' type='hidden' /></td></tr>";
		}
	}
}
?>
</table>
</form>
</html>





