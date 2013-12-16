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
if ($_REQUEST['tagIDList'] != null) {
$tagIDList = $_REQUEST['tagIDList'];

$tagID = $tagIDList[0];
} else {
$tagID = $_REQUEST['tagID'];
$tagIDList[0] = $tagID;
}
$targetDelIDTo = $_REQUEST['targetDelIDTo'];
$targetDelIDFrom = $_REQUEST['targetDelIDFrom'];
$searchType = $_REQUEST['searchType'];
if ($searchType == null) {
$searchType = 1;
}
$whereOR = "`ID`=" . join(" OR `ID`=", $tagIDList);
$whereLinkAND = "(`LINK`.`LFrom` =" . join(" AND `LINK`.`LFrom` =", $tagIDList).")";
$whereLinkOR = "(`LINK`.`LFrom` =" . join(" OR `LINK`.`LFrom` =", $tagIDList).")";
$whereAND = "`ID`=" . join(" AND `ID`=", $tagIDList);
$articleID = $_REQUEST['articleID'];
$tagEdit = htmlspecialchars($_POST['tagEdit']);
$articleEdit = htmlspecialchars($_POST['articleEdit']);
if ($targetDelIDFrom != null) {
$pdo->beginTransaction();
 $sql = "DELETE FROM `db0tagplus`.`LINK` WHERE `LINK`.`LFrom` = $targetDelIDFrom AND `LINK`.`LTo` = $targetDelIDTo;";
$pdo->exec($sql); $pdo->commit();
}
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
  $sql = "SELECT * FROM `Tag` WHERE $whereOR";
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
}
echo "serrchingtag";
print_r ($searchingTagA);
?>
<?php
$table = array();

if ($searchType == 1) {
//OR検索
	$sql = "SELECT DISTINCT `article` . * FROM  `LINK` , `article` WHERE  $whereLinkOR AND `LINK`.`LTo` =  `article`.`ID` ";
} else{
	$sql = "SELECT  `article` . * FROM  `LINK` , `article` WHERE  $whereLinkOR AND `LINK`.`LTo` =  `article`.`ID` GROUP BY  `ID` HAVING COUNT( * ) >=2";
}
print_r ($sql);
$articleSelect = $pdo->query($sql);
$k = 0;
while ($row = $articleSelect->fetch()) {
	$articleName = htmlspecialchars($row['name']);
	$articleID = htmlspecialchars($row['ID']);
	$article = array(
	'name' => $articleName,
	'ID' => $articleID
	);
	$j = 0;
	$sql = "SELECT `Tag` . * , `LINK`.`quant` ,`PTag`.`name` AS Pname FROM `User_TBL` INNER JOIN `Tag` AS PTag ON `User_TBL` . `profileID` = `PTag` . `ID`  , `LINK` ,  `Tag` WHERE  `LINK`.`LTo` =$article[ID] AND  `LINK`.`LFrom` = `Tag`.`ID`";

	$articleD = $pdo->query($sql);
	while ($row = $articleD->fetch()) {
		$tagName = htmlspecialchars($row['name']); 
		$subTagID = htmlspecialchars($row['ID']);
		$tagQuant = htmlspecialchars($row['quant']);
		$Pname = htmlspecialchars($row['Pname']);
		$owner = htmlspecialchars($row['owner']);
		$tagA = array(
		'name' => $tagName,
		'ID' => $subTagID,
		'quant' => $tagQuant,
		'Pname' => $Pname,
		'owner' => $owner
		);
		$table[$h]["tag"][$j] = $tagA;
		$j++;
		if ($taghash[$subTagID] == null) {
			$taghash[$subTagID] = array( $k++, $tagName, $subTagID, $Pname);
		}
	}
	$table[$h]["article"]= $article;
	$h++;
}
echo "タグハッシュ";
print_r ($taghash);

?>
<p>
<form action='result.php' method='post'>
<?php
if ($searchType == 0) {
echo '<input type="radio" name="searchType" value="0" checked> AND
<input type="radio" name="searchType" value="1""> OR';
} else {
echo '<input type="radio" name="searchType" value="0" "> AND
<input type="radio" name="searchType" value="1" checked> OR';
}
	$allRequest = $_REQUEST;
	print_r ($allRequest);
	foreach ($tagIDList as $tagIDsepareted){
	print $tagIDsepareted;
	echo "<input name='tagIDList[]' value='$tagIDsepareted'type='hidden' />";
	}
?>
<input type="submit" value="変更">
</form>

</p>

<table border="1" class="tablesorter">

<?php

foreach ($searchingTagA as $searchingTag) {
echo "<tr><form action='result.php' method='post'>";
	echo"<td><a href='result.php?tagID=$searchingTag[ID]' target='_blank'>$searchingTag[name]</a><input name='SearchType' value='$SearchType'type='hidden' />
		<div id='viewMainTag' onClick='editMainTag();' ><input value='編集' type='submit' name='Edit'></div>
		<div id='editMainTag'><input name='tagEdit' value='$searchingTag[name]' style='visible: hidden;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div><input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' /><input name='SearchType' value='$SearchType'type='hidden' /></td>";
};
echo "</form>";
?>
</tr>
</table>
<table border="1">
<tr>
<th><br></th>
<th><br></th>
<?php

foreach ($taghash as $key => $tagValue){

echo "<th>";
echo $tagValue[1];
echo "<br>オーナー";
echo $tagValue[3];
echo "</th>";
}
echo "</tr>";

foreach ($table as $articleA){
	echo "<tr>";
	echo "<form action='result.php' method='post'><td><a href='result.php?ID=";
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
	$k = 0;
	foreach ($searchingTagA as $searchingTag) {
		echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
		$currentSearchingTag = $searchingTag[ID];
		$k++;
	}
	echo "</td></form>";
	echo "<form action='tagresist.php' method='post'><td><div id='viewMainTag' onClick='addEachTag();' ><input value='タグ関連付け' type='submit' name=`addTag'></div>
			<div id='addTag'><input name='tagAdd' style='visible: hidden;' onChange='addEachTag();' onSubmit='submitAddTag(); return true;' /><input name='targetIDFrom' value='$tagA[ID]'type='hidden' /></div><input name='searchType' value='$searchType'type='hidden' /><input name='targetIDTo' value='";
	echo $articleA["article"][ID];
	echo "'type='hidden' />";
	echo $articleA["article"][ID];
	echo "</td></form>";
	foreach ($taghash as $key => $tagValue){
	echo "<td>";
		foreach ($articleA["tag"] as $tagA){
			if ($key == $tagA[ID]){
				echo "<form action='result.php' method='post'><br><input type='number' name='tagWeight' min='0' max='100000' value='$tagA[quant]'><input name='tagIDList[]' value='$tagA[ID]'type='hidden' /></form>";
				if (false == in_array($tagA[ID],$tagIDList)) {
					echo "<form action='result.php' method='post'><input value='絞' type='submit' name='searchAdd'><input name='tagIDList[]' value='$tagA[ID]'type='hidden' /><input name='tagIDList[]' value='$currentSearchingTag'type='hidden' /><input name='searchType' value='$searchType'type='hidden' /></form>";
				}
				echo "<form action='result.php' method='post'><input value='削除' type='submit' name='tagDel'>";
				echo "<input name='targetDelIDTo' value='";
				echo $articleA["article"][ID];
				echo "'type='hidden' />";
				echo "<input name='targetDelIDFrom' value='$tagA[ID]'type='hidden' /><input name='tagIDList[]' value='$currentSearchingTag'type='hidden' /><input name='searchType' value='$searchType'type='hidden' /><a href='result.php?tagID=$tagA[ID]' target='_blank'>$tagA[name]</a></form>";
			} else {
			}
		}
	echo "</td>";
	} 
	echo "</tr>"; 

}

?>
</table>
</body>
</html>





