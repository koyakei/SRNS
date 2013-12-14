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
$table = array();
$sql = "SELECT  `article` . * FROM  `LINK` ,  `article` WHERE  `LINK`.`LFrom` =$tagID AND  `LINK`.`LTo` =  `article`.`ID` ";
	$articleSelect = $pdo->query($sql);
	while ($row = $articleSelect->fetch()) {
		$articleName = htmlspecialchars($row['name']);
		$articleID = htmlspecialchars($row['ID']);
		$article = array(
		'name' => $articleName,
		'ID' => $articleID
		);
		
	$j = 0;
		$sql = "SELECT `Tag` . * FROM  `LINK` ,  `Tag` WHERE  `LINK`.`LTo` =$article[ID] AND  `LINK`.`LFrom` = `Tag`.`ID` ";
		$articleD = $pdo->query($sql);
		while ($row = $articleD->fetch()) {
			$tagName = htmlspecialchars($row['name']); 
			$subTagID = htmlspecialchars($row['ID']);
			$tagA = array(
			'name' => $tagName,
			'ID' => $subTagID
			);
			$table[$h]["tag"][$j] = $tagA;
			$j++;
		}
		$table[$h]["article"]= $article;
		$h++;
	}
?>
<form action='result.php' method='post'>
<table border="1" class="tablesorter">
<?php
foreach ($searchingTagA as $searchingTag) {
	echo "<tr><td><a href='result.php?ID=$searchingTag[ID]' target='_blank'>$searchingTag[name]</a>
		<div id='viewMainTag' onClick='editMainTag();' ><input value='編集' type='submit' name='Edit'></div>
		<div id='editMainTag'><input name='tagEdit' value='$searchingTag[name]' style='visible: hidden;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div><input name='tagID' value='$searchingTag[ID]' type='hidden' /></td></tr>";
};
?>
</table>
</form>
<form action='result.php' method='post'>
<table border="1">
<?php
foreach ($table as $articleA){
echo "<tr><td><a href='result.php?ID=";
echo $articleA["article"][ID];
echo "' target='_blank'>";
echo $articleA["article"][name];
echo "</a>
		<div id='viewMainTag' onClick='editArticle();' ><input value='編集' type='submit' name='Edit'></div>
		<div id='editMainTag'><input name='articleEdit' value='";
echo $articleA["article"][name];
echo "' style='visible: hidden;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div><input name='articleID' value='";
echo $articleA["article"][ID];
echo "' type='hidden' />";
echo "</td>";


foreach ($articleA["tag"] as $tagA){
	
	echo "<td><a href='result.php?tagID=$tagA[ID]' target='_blank'>$tagA[name]</a><input name='tagID' value='$tagA[ID]' type='hidden' /></td>";

}
echo "<td><div id='viewMainTag' onClick='addEachTag();' ><input value='追加' type='submit' name='Add'></div>
		<div id='addTag'><input name='tagAdd' style='visible: hidden;' onChange='addEachTag();' onSubmit='submitAddTag(); return true;' /></div></td>";
echo "</tr>";
}

?>
</table>
</form>
</html>





