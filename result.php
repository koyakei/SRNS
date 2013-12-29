<html>
<head>

<link href="css/sitemapstyler.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/sitemapstyler.js"></script>

<link rel="stylesheet" type="text/css" href="css/tablesorter.css" />
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css" href="css/k.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="js/sorttable.js" type="text/javascript"></script>
<script type="text/javascript" src="js/dragtable.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">

function toggleShow(obj) {
 var ch = obj.parentNode.children;
 for (var i = 0, len = ch.length; i < len; i++) {
    if (ch[i].getAttribute("id") == "HSfield") {
      var element = ch[i];
         if (element.style.display == 'none') {
           element.style.display='block';
         } else {
           element.style.display='none';
         }
       }
    }
 }
</script>

<title>Top</title>
</head>
<body>

<?php
ini_set( 'display_errors', 1 );
require_once("cmn/debug.php");
require_once("cmn/utils.php");
require_once("cmn/functions.php");
$pdo = db_open();
$articleAdd = $_REQUEST['articleAdd'];
$replyName = $_REQUEST['articleReply'];

$tagWeight = $_REQUEST['tagWeight'];//weight 変更
$targetLinkID = $_REQUEST['targetLinkID'];
if ($tagWeight != null) {
$pdo->beginTransaction();
 $sql = "UPDATE `db0tagplus`.`LINK` SET `quant` = $tagWeight WHERE `LINK`.`ID` = $targetLinkID;";
$pdo->exec($sql); $pdo->commit();
}
$ownerID = 1;//今は管理者にしている
//$replyTagID = 2138;//返信ID　tag;reply
$tagName = $_REQUEST['addTagRelation'];
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
if ($articleAdd != null) {
	$pdo->beginTransaction();//記事を追加する
	$sql = "INSERT INTO  `db0tagplus`.`article` (
	`ID` ,
	`name` ,
	`owner` ,
	`Created_time`
	)
	VALUES (
	NULL ,  '$articleAdd', '1', NOW( )
	);";
	$pdo->exec($sql); 
	$LINK1stID = $pdo->lastInsertId('ID');
	$pdo->commit();
	//タグを追加する
	foreach ($tagIDList as $targetTagID) {
		$pdo->beginTransaction();//既存の記事とタグのリンク作成
		$sql = "INSERT INTO  `db0tagplus`.`LINK` (
		`ID` ,
		`LFrom` ,
		`LTo` ,
		`quant` ,
		`owner`
		)
		VALUES (
		NULL ,  '$targetTagID',  '$LINK1stID',  '1',  '1'
		);";
		$pdo->exec($sql); $pdo->commit(); 
	}
}
	
if ( $articleID != null and $replyName != null) {
	$pdo->beginTransaction();//返事を追加する
	$sql = "INSERT INTO  `db0tagplus`.`article` (
	`ID` ,
	`name` ,
	`owner` ,
	`Created_time`
	)
	VALUES (
	NULL ,  '$replyName', '1', NOW( )
	);";
	$pdo->exec($sql); 
	$LINK1stID = $pdo->lastInsertId('ID');
	$pdo->commit();
	$pdo->beginTransaction();//元記事と返信記事のリンクを作成
	$sql = "INSERT INTO  `db0tagplus`.`LINK` (
	`ID` ,
	`LFrom` ,
	`LTo` ,
	`quant` ,
	`owner`,
	`Created_time`
	)
	VALUES (
	NULL ,  '$articleID',  '$LINK1stID',  '1',  '$ownerID', NOW( )
	);";
	$pdo->exec($sql); 
	$lastAIID = $pdo->lastInsertId('ID');//最後に追加したLINK　テーブルのIDを取得
	$pdo->commit();
	$pdo->beginTransaction();//元記事-返信リンクと返信タグリンクを作成
	$sql = "INSERT INTO  `db0tagplus`.`LINK` (
	`ID` ,
	`LFrom` ,
	`LTo` ,
	`quant` ,
	`owner`,
	`Created_time`
	)
	VALUES (
	NULL ,  '$replyTagID',  '$lastAIID',  '1',  '$ownerID', NOW( )
	);";
	$pdo->exec($sql); 
	$pdo->commit();
}
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
if ($tagEdit != null) {//タグ編集
	$pdo->beginTransaction();
	 $sql = "UPDATE `db0tagplus`.`Tag` SET `name` = '$tagEdit' WHERE `Tag`.`ID` = $tagID;";
	$pdo->exec($sql); $pdo->commit();
	  $sql = "SELECT '$tagEdit' as name, '$tagID' as ID FROM `Tag` WHERE `ID` =$tagID";
} else {
  	$sql = "SELECT * FROM `Tag` WHERE $whereOR";
}
$tagG = $pdo->query($sql);
$i = 0;
while ($row = $tagG->fetch()) {//タグIDを取得
	$name = htmlspecialchars($row['name']);
	$ID = htmlspecialchars($row['ID']);
	$searchingTag = array(
		'name' => $name,
		'ID' => $ID
	);
	$searchingTagA[$i] = $searchingTag;
	$i++;
}
?>
<?php
//タグ検索関連
$table = array();

if ($searchType == 1) {//記事取得
//OR検索
	$sql = "SELECT DISTINCT `article` . * FROM  `LINK` , `article` WHERE  $whereLinkOR AND `LINK`.`LTo` =  `article`.`ID` ";
} else{
	$sql = "SELECT  `article` . * FROM  `LINK` , `article` WHERE  $whereLinkOR AND `LINK`.`LTo` =  `article`.`ID` GROUP BY  `ID` HAVING COUNT( * ) >=2";
}
$articleSelect = $pdo->query($sql);
$k = 0;
while ($row = $articleSelect->fetch()) {
	$articleName = htmlspecialchars($row['name']);
	$articleID = htmlspecialchars($row['ID']);
	$article = array(
	'name' => $articleName,
	'ID' => $articleID
	);
	//リプライ取得
	$o = 0;
	$sql = "SELECT  `tagLink`.`LFrom` AS TLFROM, `article` . * FROM  `LINK` INNER JOIN  `LINK` AS tagLink ON  `LINK`.`ID` = `tagLink`.`LTo`, `article`  WHERE  `LINK`.`LFrom` =$article[ID] AND `tagLink`.`LFrom` =2138  AND `article` . `ID` = `LINK` . `LTo`";
	$ReplySQL = $pdo->query($sql);
	while ($row = $ReplySQL->fetch()) {
		$replyName = htmlspecialchars($row['name']); 
		$replyID = htmlspecialchars($row['ID']);
		$ownerID = htmlspecialchars($row['owner']);
		$CreatedTime = htmlspecialchars($row['Created_time']);
		$replyA = array(
		'name' => $replyName,
		'owner' => $ownerID,
		'CreatedTime' => $CreatedTime,
		'ID' => $replyID
		);
		$table[$h]["reply"][$o] = $replyA;
		$o++;
	}
	//リプライ取得終了
	$j = 0;//タグのリンク・タグ名　重さ取得
	$sql = "SELECT `Tag` . * , `LINK`.`quant` ,`LINK`.`ID` as linkID ,`PTag`.`name` AS Pname FROM `User_TBL` INNER JOIN `Tag` AS PTag ON `User_TBL` . `profileID` = `PTag` . `ID`  , `LINK` ,  `Tag` WHERE  `LINK`.`LTo` =$article[ID] AND  `LINK`.`LFrom` = `Tag`.`ID`";
	$articleD = $pdo->query($sql);
	while ($row = $articleD->fetch()) {
		$tagName = htmlspecialchars($row['name']); 
		$subTagID = htmlspecialchars($row['ID']);
		$tagQuant = htmlspecialchars($row['quant']);
		$Pname = htmlspecialchars($row['Pname']);
		$owner = htmlspecialchars($row['owner']);
		$linkID = htmlspecialchars($row['linkID']);
		$tagA = array(
		'name' => $tagName,
		'ID' => $subTagID,
		'quant' => $tagQuant,
		'Pname' => $Pname,
		'owner' => $owner,
		'linkID' => $linkID
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
?>
<form action='result.php' method='post'>
<?php
if ($searchType == 0) {
echo '<input type="radio" name="searchType" value="0" checked> AND
<input type="radio" name="searchType" value="1"> OR';
} else {
echo '<input type="radio" name="searchType" value="0"> AND
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
<table border="1">
<tr>
<?php
foreach ($searchingTagA as $searchingTag) {//タグを表示する
echo "<form action='result.php' method='post'>";//タグの編集
	echo "<td><a href='result.php?tagID=$searchingTag[ID]' target='_blank'>$searchingTag[name]</a><input name='SearchType' value='$SearchType'type='hidden' />
		<div id='viewMainTag' onClick='showHide();' ><input value='編集' type='submit' name='Edit'></div>
		<div id='editMainTag'><input name='tagEdit' value='$searchingTag[name]' style='visible: hidden;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div><input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' /><input name='SearchType' value='$SearchType'type='hidden' />";

	echo "</td>";
};
echo "</form>";
?>
<td>
<div onClick="toggleShow(this);">
記事追加
</div>
<div id="HSfield" style="display: none;">
<?php
	echo "<form action='result.php' method='post'><input value='記事追加' type='submit' name='addArticle'>";
	foreach ($searchingTagA as $searchingTag) {
		echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
	}
	echo "<input name='articleAdd'type='text' /></form>";

?>
</div>
</td>
</tr>
</table>

<table class="sortable draggable" border="1">
<thead>
<tr>
<th class="sorttable_nosort">


</th>
<th></th>
<?php
foreach ($taghash as $key => $tagValue){
echo "<th>";
echo $tagValue[1];
echo "<br>owner";
echo $tagValue[3];
echo "</th>";
echo "<th>";
echo "</th>";
}
?>
</tr></thead><tbody>
<?php
$p = 0;
foreach ($table as $articleA){
	echo "<tr>";
	echo "<td><form action='result.php' method='post'>";
	echo "<a href='articleDetale.php?ID=";
	echo $articleA["article"][ID];
	echo "' target='_blank'>";
	echo $articleA["article"][name];
	echo "</a>";
	echo "<div onClick='toggleShow(this);'>";
	echo "編集";
	echo "</div>";
	echo "<div id='HSfield' style='display: none;'>";//返信展開開始
	echo "<div id='viewMainTag' onClick='editArticle();' ><input value='編集' type='submit' name='Edit'></div>";
	echo "<div id='editMainTag'><input name='articleEdit' value='";
	echo $articleA["article"][name];
	echo "' style='visible' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div>";
	echo "<input name='articleID' value='";
	echo $articleA["article"][ID];
	echo "' type='hidden' />";
	foreach ($searchingTagA as $searchingTag) {
		echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
	}
	echo "</form>";

	echo "<form action='result.php' method='post'>";
	foreach ($searchingTagA as $searchingTag) {
		echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
	}
	echo "<input name='replyID' value='";
	echo $articleA["article"][ID];
	echo "' type='hidden' /></form>";
	echo "</div>";
	if ($articleA["reply"] != null) {
		echo "<div onClick='toggleShow(this);'>";
		echo "返信をすべて開く";
		echo "</div>";
		echo "<div id='HSfield' style='display;none;'>";
		foreach ($articleA["reply"] as  $Reply) {
			if (is_url($Reply[name]) == TRUE) {//返信表示
				echo "<a href='";
				echo $Reply[name];
				echo "' target='_blank'>";
				echo $Reply[name];
				echo "</a>";
			} else {
			echo $Reply[name];
			}
			echo "<form action='result.php' method='post'>";//返信削除フォーム開始
			echo "<input value='返信の削除' type='submit' name='articleReply'>";//ボタン
			foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
				echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
			}
			echo "<input name='reblyIDDel' value='";
			echo $Reply[ID];
			echo "' type='hidden' />";//返信記事ID取得
			echo "<input name='articleID' value='";
			echo $Reply[ID];
			echo "' type='hidden' />";//記事ID取得
			echo "</form>";//返信フォーム終了
	//返事表示
			echo "<form action='result.php' method='post'><div onClick='toggleShow(this);'>"; //返事を書き直す
			echo "Edit Reply返事を書き直す";
			echo "</div>";
			
			echo "<div id='HSfield' style='display;none;'>";//返事の編集開始
			echo "<input value='返信の編集' type='submit' name='articleReply'>";
			echo "<input name='articleAdd' style=display;none;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' />";
			echo "</div></form>";//返事の編集終了
		}
		echo "</div>";//返信展開終了
	}
	echo "</td>";
	echo "<td><form action='tagresist.php' method='post'>";
	echo "<input value='タグ関連付け' type='submit' name=`addTag'>";
	echo "<div id='addTag'><input name='tagAdd' style='visible: hidden;' onChange='addEachTag();' onSubmit='submitAddTag(); return true;' /><input name='targetIDFrom' value='$tagA[ID]'type='hidden' /></div>";
	echo "<input name='searchType' value='$searchType'type='hidden' /><input name='targetIDTo' value='";
	echo $articleA["article"][ID];
	echo "'type='hidden' />";
	echo "</form>";
	echo "<div onClick='toggleShow(this);'>";
	echo "返信フォーム";
	echo "</div>";
	echo "<div id='HSfield' style='display: none;'>";
		echo "<form action='result.php' method='post'>";//返信フォーム開始
		echo "<input value='記事への返信' type='submit' name='articleReply'>";//ボタン
		echo "<input name='articleReply'/>";
		foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
			echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
		}
		echo "<input name='articleID' value='";
		echo $articleA["article"][ID];
		echo "' type='hidden' />";//記事ID取得
		echo "</form>";//返信フォーム終了
	echo "</div>";

	echo "</td>";
	//リンクの重さ
	foreach ($taghash as $key => $tagValue){
		echo "<td>";
		foreach ($articleA["tag"] as $tagA){
			if ($key == $tagA[ID]){
				echo "$tagA[quant]</td>";//リンクの重さ
				echo "<td>";
				if (false == in_array($tagA[ID],$tagIDList)) {
					echo "<form action='result.php' method='post'><input value='絞' type='submit' name='searchAdd'><input name='tagIDList[]' value='$tagA[ID]'type='hidden' />";
					foreach ($searchingTagA as $searchingTag) {
						echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
					}
					echo "<input name='searchType' value='$searchType'type='hidden' /></form>";
				}
				echo "<form action='result.php' method='post'>";//重さ変更ポスト
				echo "<input type='number' name='tagWeight' min='0' max='100000' value='$tagA[quant]'><input name='targetLinkID' value='$tagA[linkID]'type='hidden' /><input value='重さ変更' type='submit' name='weghit'><input name='searchType' value='$searchType'type='hidden' /><input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
				echo "</form>";//重さ変更ポスト終了
				
				echo "<form action='result.php' method='post'><input value='削除' type='submit' name='tagDel'>";
				echo "<input name='targetDelIDTo' value='";//削除
				echo $articleA["article"][ID];
				echo "'type='hidden' />";
				foreach ($searchingTagA as $searchingTag) {
					echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
				}
				echo "<input name='targetDelIDFrom' value='$tagA[ID]'type='hidden' /><input name='searchType' value='$searchType'type='hidden' /><a href='result.php?tagID=$tagA[ID]' target='_blank'>$tagA[name]</a>";
				echo "</form>"; 
				
				$p++;
				echo "<ul id='tagSearchRelation$p'><li>検索関係<ul>";
				/*
				foreach (){
					echo "<li><a href='result.php?tagID=$relationTag[ID]'>$relationTag[name]関係付けられているタグ</a></li>":
				}*/

				echo "</ul></li></ul>";
				echo "<form action='result.php' method='post'>";//親タグに対して検索関係追加	
				echo "<input name='targetDelIDFrom' value='$tagA[ID]'type='hidden' />"; //当該タグID　追加nameを送信する
				echo "<input value='追加' type='submit' name='addArticle'><input name='addTagRelation' type='text' /></form>";
			}
		}
		echo "</td>"; 
	}
	echo "</tr>";
} 

?>
</tbody>
</table>
</body>
</html>