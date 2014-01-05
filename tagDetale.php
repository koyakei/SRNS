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
<link href="css/sitemapstyler.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/sitemapstyler.js"></script>
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
require_once("cmn/specialTagIDList.php");
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
$tagName = $_REQUEST['addTagRelation'];
$tagDetaleID = $_REQUEST['tagDetaleID'];
$articleDetaleID = $_REQUEST['articleDetaleID'];
if ($_REQUEST['tagIDList'] != null) {
$tagIDList = $_REQUEST['tagIDList'];

$tagID = $tagIDList[0];
} else {
$tagID = $tagDetaleID;
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
$addTagWithTagRelation = $_REQUEST['addTagWithTagRelation'];//タグをタグに検索で関連付けて追加
if ($addTagWithTagRelation != null) {
        $sql = "SELECT `Tag`.*  , `userP`.`name` AS userProfile FROM `Tag` AS userP ,`User_TBL` INNER JOIN `Tag` ON `User_TBL` . `profileID` = `Tag` . `owner`   WHERE  `Tag` .`name` LIKE  '$addTagWithTagRelation' AND `userP`.`ID` = `User_TBL` . `profileID`";
        $tagIDfinding = $pdo->query($sql);//既存のタグを探す一層の並列関係のみ
        $i = 0;
        while ($row = $tagIDfinding->fetch()) {
                $name = htmlspecialchars($row['name']);
                $ID = htmlspecialchars($row['ID']);
                $owner = htmlspecialchars($row['owner']);
                $userProfile = htmlspecialchars($row['userProfile']);
                $Tag = array(
                        'name' => $name,
                        'ID' => $ID,
                        'ownerID' => $owner,
                        'userProfile' => $userProfile
                );
                $tagA[$i] = $Tag; 
                $i++;
        }
        if ($tagA == null) {
                echo "タグを登録してください";
                $pdo->beginTransaction();//現在検索しているタグに新規追加するタグを関連させながら追加
                $sql = "INSERT INTO  `db0tagplus`.`Tag` (
                `ID` ,
                `name` ,
                `owner` ,
                `Created_time`
                )
                VALUES (
                NULL ,  '$addTagWithTagRelation', '$ownerID', NOW( )
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
                        NULL ,  '$searchTagID',  '$lastAIID',  '1',  '$ownerID', NOW( )
                        );";
                        $pdo->exec($sql); 
                }
        } else {
                foreach ($tagA as $eachTag){
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
                                NULL ,  '$targetTagID',  '$eachTag[ID]',  '1',  '$ownerID'
                                );";
                                $pdo->exec($sql);
                                $lastAIID = $pdo->lastInsertId('ID');//最後に追加したLINK　テーブルのIDを取得
                                $pdo->commit();
                                $pdo->beginTransaction();//元記事-返信リンクと検索タグリンクを作成
                                $sql = "INSERT INTO  `db0tagplus`.`LINK` (
                                `ID` ,
                                `LFrom` ,
                                `LTo` ,
                                `quant` ,
                                `owner`,
                                `Created_time`
                                )
                                VALUES (
                                NULL ,  '$searchTagID',  '$lastAIID',  '1',  '$ownerID', NOW( )
                                );";
                                $pdo->exec($sql); 
                                $pdo->commit(); 
                        }
                }
        }
}
if ($ReplyTag != null) {//返事としてタグを追加する
        $sql = "SELECT `Tag`.*  , `userP`.`name` AS userProfile FROM `Tag` AS userP ,`User_TBL` INNER JOIN `Tag` ON `User_TBL` . `profileID` = `Tag` . `owner`   WHERE  `Tag` .`name` LIKE  '$ReplyTag' AND `userP`.`ID` = `User_TBL` . `profileID`";
        $tagIDfinding = $pdo->query($sql);//既存のタグを探す一層の並列関係のみ
        $i = 0;
        while ($row = $tagIDfinding->fetch()) {
                $name = htmlspecialchars($row['name']);
                $ID = htmlspecialchars($row['ID']);
                $owner = htmlspecialchars($row['owner']);
                $userProfile = htmlspecialchars($row['userProfile']);
                $Tag = array(
                        'name' => $name,
                        'ID' => $ID,
                        'ownerID' => $owner,
                        'userProfile' => $userProfile
                );
                $tagA[$i] = $Tag; 
                $i++;
        }
        if ($tagA == null) {//既に登録されていなかったら、
                echo "タグを登録してください";
                $pdo->beginTransaction();//現在検索しているタグに新規追加するタグを関連させながら追加
                $sql = "INSERT INTO  `db0tagplus`.`Tag` (
                `ID` ,
                `name` ,
                `owner` ,
                `Created_time`
                )
                VALUES (
                NULL ,  '$articleReplyTag', '$ownerID', NOW( )
                );";
                $pdo->exec($sql); 
                $LINK1stID = $pdo->lastInsertId('ID');
                $pdo->commit();
                //タグを追加する
                $pdo->beginTransaction();//既存の記事とタグのリンク作成
                $sql = "INSERT INTO  `db0tagplus`.`LINK` (
                `ID` ,
                `LFrom` ,
                `LTo` ,
                `quant` ,
                `owner`
                )
                VALUES (
                NULL ,  '$targetID',  '$LINK1stID',  '1',  '1'
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
        } else {
                foreach ($tagA as $eachTag){
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
                                NULL ,  '$targetID',  '$eachTag[ID]',  '1',  '$ownerID'
                                );";
                                $pdo->exec($sql);
                                $lastAIID = $pdo->lastInsertId('ID');//最後に追加したLINK　テーブルのIDを取得
                                $pdo->commit();
                                $pdo->beginTransaction();//元記事-返信リンクと検索タグリンクを作成
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
                }
        }
}
if ($articleAdd != null) {//記事を追加する
        $pdo->beginTransaction();//返事記事を追加する
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

if ($replyAdd != null  and is_array($tryArticleAdd)) {
	$tryArticleAdd[ownerID] = 1;
	$DBSQL = new sqlComp();
	$DBSQL->replyAdd($tryArticleAdd);
}
/*
if ($articleID != null and $replyName != null) {
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
*/
$targetDelLinkID = $_REQUEST['targetDelLinkID'];
if ($targetDelLinkID != null) {	
	$DBSQL = new sql();
	$DBSQL->open();
	$DBSQL->delRelation($targetDelLinkID);
}
if ($articleEdit != null && is_array($articleEdit)) {
	$DBSQL = new sql();
	$DBSQL->open();
	$DBSQL->articleEdit($articleEdit);
}
if ($tagEdit != null && is_array($tagEdit)) {//タグ編集
	$DBSQL = new sql();
	$DBSQL->open();
	$DBSQL->tagEdit($tagEdit);
	//$sql = "SELECT '$tagEdit' as name, '$tagID' as ID FROM `Tag` WHERE `ID` =$tagID";//タグ選択　記事取得
}
if ($articleDetaleID != null){
        $sql = "SELECT * FROM `article` WHERE `ID`=$articleDetaleID";//複数条件の時のタグ選択　記事取得
} else {
        $sql = "SELECT * FROM `Tag` WHERE `ID`=$tagDetaleID";
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
        $searchingTagA[$i] = $searchingTag;//サジェスト取得
        $sql = "SELECT  `tagLink`.`LFrom` AS TLFROM, `Tag` . * FROM  `LINK` INNER JOIN  `LINK` AS tagLink ON  `LINK`.`ID` = `tagLink`.`LTo`, `Tag`  WHERE  `LINK`.`LFrom` =$searchingTag[ID] AND `tagLink`.`LFrom` =$tagSSugID  AND `Tag` . `ID` = `LINK` . `LTo`";
        $tagSuggestSQL = $pdo->query($sql);
        $o = 0;
        while ($row = $tagSuggestSQL->fetch()) {
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
                $searchingTagA[$i]["tagSSug"][$o] = $replyA;
                $o++;
        }
        $i++;
        
}
?>
<?php
if ($tagDetaleID != null){
$detaleID = $tagDetaleID;
} else {
$detaleID = $articleDetaleID;
}
print_r ($detaleID);
//タグ検索関連
$table = array();
$k = 0;//タグハッシュ
$h = 0;
$sql = "SELECT  `tagLink`.`LFrom` AS TLFROM, `article` . *, `LINK`.`ID` AS LinkID FROM  `LINK` INNER JOIN  `LINK` AS tagLink ON  `LINK`.`ID` = `tagLink`.`LTo`, `article`  WHERE  `LINK`.`LFrom` =$detaleID AND `tagLink`.`LFrom` =$replyTagID  AND `article` . `ID` = `LINK` . `LTo`";
$articleSelect1 = $pdo->query($sql);
while ($row = $articleSelect1->fetch()) {
        $articleName = htmlspecialchars($row['name']);
        $articleID = htmlspecialchars($row['ID']);
        $ownerID = htmlspecialchars($row['owner']);
        $CreatedTime = htmlspecialchars($row['Created_time']);
        $LinkID = htmlspecialchars($row['LinkID']);

        $article = array(
        'name' => $articleName,
        'owner' => $ownerID,
        'CreatedTime' => $CreatedTime,
        'LinkID' => $LinkID,
        'ID' => $articleID
        );
        //記事取得
        $o = 0;
        $sql = "SELECT  `tagLink`.`LFrom` AS TLFROM, `article` . *, `LINK`.`ID` AS LinkID FROM  `LINK` INNER JOIN  `LINK` AS tagLink ON  `LINK`.`ID` = `tagLink`.`LTo`, `article`  WHERE  `LINK`.`LFrom` =$article[ID] AND `tagLink`.`LFrom` =$replyTagID  AND `article` . `ID` = `LINK` . `LTo`";
        $ReplySQL = $pdo->query($sql);
        while ($row = $ReplySQL->fetch()) {
                $replyName = htmlspecialchars($row['name']); 
                $replyID = htmlspecialchars($row['ID']);
                $ownerID = htmlspecialchars($row['owner']);
                $CreatedTime = htmlspecialchars($row['Created_time']);
                $LinkID = htmlspecialchars($row['LinkID']);
                $replyA = array(
                'name' => $replyName,
                'owner' => $ownerID,
                'CreatedTime' => $CreatedTime,
                'LinkID' => $LinkID,
                'ID' => $replyID
                );
                $table[$h]["reply"][$o] = $replyA;
                $o++;
        }
        //リプライ取得終了
        $j = 0;//タグのリンク・リンク取得
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
$table2 = array();
$h = 0;
$sql = "SELECT  `tagLink`.`LFrom` AS TLFROM, `Tag` . *, `LINK`.`ID` AS LinkID FROM  `LINK` INNER JOIN  `LINK` AS tagLink ON  `LINK`.`ID` = `tagLink`.`LTo`, `Tag`  WHERE  `LINK`.`LFrom` =$detaleID AND `tagLink`.`LFrom` =$replyTagID  AND `Tag` . `ID` = `LINK` . `LTo`";
$articleSelect = $pdo->query($sql);
while ($row = $articleSelect->fetch()) {
        $articleName = htmlspecialchars($row['name']);
        $articleID = htmlspecialchars($row['ID']);
        $ownerID = htmlspecialchars($row['owner']);
        $CreatedTime = htmlspecialchars($row['Created_time']);
        $LinkID = htmlspecialchars($row['LinkID']);
        $article = array(
        'name' => $articleName,
        'owner' => $ownerID,
        'CreatedTime' => $CreatedTime,
        'LinkID' => $LinkID,
        'ID' => $articleID
        );
        //返事取得
        $o = 0;
        $sql = "SELECT  `tagLink`.`LFrom` AS TLFROM, `article` . *, `LINK`.`ID` AS LinkID FROM  `LINK` INNER JOIN  `LINK` AS tagLink ON  `LINK`.`ID` = `tagLink`.`LTo`, `article`  WHERE  `LINK`.`LFrom` =$article[ID] AND `tagLink`.`LFrom` =$replyTagID  AND `article` . `ID` = `LINK` . `LTo`";//返事の記事を取得する
//記事に対する返事は記事しか表示されないように今のところはしておこう。検索サジェストで対応する。
        $Reply2SQL = $pdo->query($sql);
        while ($row = $Reply2SQL->fetch()) {
                $replyName = htmlspecialchars($row['name']); 
                $replyID = htmlspecialchars($row['ID']);
                $ownerID = htmlspecialchars($row['owner']);
                $CreatedTime = htmlspecialchars($row['Created_time']);
                $LinkID = htmlspecialchars($row['LinkID']);
                $replyA = array(
                'name' => $replyName,
                'owner' => $ownerID,
                'CreatedTime' => $CreatedTime,
                'LinkID' => $LinkID,
                'ID' => $replyID
                );
                $table2[$h]["reply"][$o] = $replyA;
                $o++;
        }
        //リプライ取得終了
        $j = 0;//タグのリンク・リンク取得
        $sql = "SELECT `Tag` . * , `LINK`.`quant` ,`LINK`.`ID` as linkID ,`PTag`.`name` AS Pname FROM `User_TBL` INNER JOIN `Tag` AS PTag ON `User_TBL` . `profileID` = `PTag` . `ID`  , `LINK` ,  `Tag` WHERE  `LINK`.`LTo` =$article[ID] AND  `LINK`.`LFrom` = `Tag`.`ID`";
        $articleD2 = $pdo->query($sql);
        while ($row = $articleD2->fetch()) {
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
                $table2[$h]["tag"][$j] = $tagA;
                $j++;
                if ($taghash[$subTagID] == null) {
                        $taghash[$subTagID] = array( $k++, $tagName, $subTagID, $Pname);
                }
        }
        $table2[$h]["tag1"]= $article;
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
        //print_r ($allRequest);
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
$p = 0;
foreach ($searchingTagA as $searchingTag) {//タグを表示する
        echo "<form action='result.php' method='post'>";//タグの編集
        echo "<td>";
	if ($tagDetaleID != null) {
		echo "<a href='result.php?tagID=$searchingTag[ID]' target='_blank'>$searchingTag[name]</a>";
	} else {
		echo $searchingTag[name];
	}
	echo "<input name='SearchType' value='$SearchType'type='hidden' />
                <div id='viewMainTag' onClick='showHide();' ><input value='編集' type='submit' name='Edit'></div>
                <div id='editMainTag'><input name='tagEdit' value='$searchingTag[name]' style='visible: hidden;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div><input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' /><input name='SearchType' value='$SearchType'type='hidden' /></form>";
        echo "<form action='tagRelationResist.php' method='post'>";//親タグに対して検索関係追加        
        echo "<input name='targetTagIDFrom' value='$searchingTag[ID]'type='hidden' />";
        //当該タグID　追加nameを送信する
        echo "<input value='追加' type='submit' name='addArticle'><input name='addTagName' type='text' /></form>";//追加フォーム終了
	
        echo "<ul id='tagSearchRelation$p'><li>検索関係<ul>";
        $p++;
        if ($searchingTag[tagSSug] != null){
                foreach ($searchingTag[tagSSug] as $tagSug){
                        echo "<li><a href='result.php?tagID=$tagSug[ID]'>$tagSug[name]</a></li>";
                        
                }
        }
        echo "</ul></li></ul>";
        echo "</td>";
}

?>
<td>
<div onClick="toggleShow(this);">
記事追加
</div>
<div id="HSfield" style="display: none;">
<?php
        echo "<form action='tagDetale.php' method='post'><input value='記事追加' type='submit' name='replyAdd'>";
        foreach ($searchingTagA as $searchingTag) {
                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
        }
	 echo "<input name='tagDetaleID' value='$tagDetaleID'type='hidden' />";
	 echo "<input name='triArticleAdd[fromID]' value='$tagDetaleID'type='hidden' />";
        echo "<input name='triArticleAdd[name]'type='text' /></form>";

?>
</div>
<div onClick="toggleShow(this);">
タグ追加
</div>
<div id="HSfield" style="display: none;">
<?php
        echo "<form action='result.php' method='post'><input value='タグ追加' type='submit' name='addTagWithTagRelationBtn'>";
        foreach ($searchingTagA as $searchingTag) {
                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
        }
        echo "<input name='addTagWithTagRelation'type='text' /></form>";

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
print_r ($taghash);
foreach ($taghash as $key => $tagValue){
echo "<th><a href='result.php?tagID=$tagValue[2]' target='_blank'>$tagValue[1]</a>";
echo "<br>owner";
echo $tagValue[3];
echo "</th>";
echo "<th>";
echo "</th>";
}
?>
</tr></thead><tbody>
<?php

foreach ($table as $articleA){//記事表示ループ
        echo "<tr>";
        echo "<td><form action='result.php' method='post'>";
        if (is_url($articleA["article"][name]) == TRUE) {//返信表示
                echo "<a href='";
                echo $articleA["article"][name];
                echo "' target='_blank'>";
                echo $articleA["article"][name];
                echo "</a>";
        } else {
                echo "<a href='tagDetale.php?tagID=";
                echo $articleA["article"][ID];
                echo "' target='_blank'>";
                echo $articleA["article"][name];
                echo "</a>";
        }
        echo "<form action='result.php' method='post'>";//記事削除フォーム開始
        echo "<input value='記事の削除' type='submit' name='articleReply'>";//ボタン
        foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
        }
        echo "<input name='targetDelLinkID[]' value='";
        echo $articleA["article"][LinkID];
        echo "' type='hidden' />";//返信記事ID取得
        echo "</form>";//記事削除フォーム終了

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
                        echo "<a href='tagDetale.php?articleID=$Reply[ID]' target='_blank'>$Reply[name]</a>";
                        }
                        echo "<form action='result.php' method='post'>";//返信削除フォーム開始
                        echo "<input value='返信の削除' type='submit' name='articleReply'>";//ボタン
                        foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
                        }
                        echo "<input name='targetDelLinkID[]' value='";
                        echo $Reply[LinkID];
                        echo "' type='hidden' />";//返信記事ID取得
                        echo "</form>";//返信削除フォーム終了
        //返事表示
                        echo "<form action='result.php' method='post'><div onClick='toggleShow(this);'>"; //返事を書き直す
                        echo "Edit Reply返事を書き直す";
                        echo "</div>";
                        echo "<input name='articleID' value='";
                        echo $Reply[ID];
                        echo "' type='hidden' />";//記事ID取得
                        foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
                        }
                        echo "<div id='HSfield' style='display;none;'>";//返事の編集開始
                        echo "<input value='返信の編集' type='submit' name='articleReply'>";
                        echo "<input name='articleEdit' style=display;none;' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' />";
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
        echo "コメント記事";
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
        echo "</div>";/*
                echo "<div onClick='toggleShow(this);'>";
        echo "コメントタグ";
        echo "</div>";
        echo "<div id='HSfield' style='display: none;'>";
                echo "<form action='result.php' method='post'>";//返信フォーム開始
                echo "<input value='記事への返信タグ' type='submit' name='articleReply'>";//ボタン
                echo "<input name='ReplyTag'/>";
                foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                        echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
                }
                echo "<input name='targetID' value='";
                echo $articleA["article"][ID];
                echo "' type='hidden' />";//記事ID取得
                echo "</form>";//返信フォーム終了
        echo "</div>";*/
        echo "</td>";
        //リンクの重さ
        foreach ($taghash as $key => $tagValue){//タグの数だけ回す
                echo "<td>";
                $isContain = false;
                foreach ($articleA["tag"] as $tagA){//各タグの列２つ分について全検索タグと比較このループで一回も一致しなかったら
                        if ($key == $tagA[ID]){//キーが当該IDと一致したら
                                $isContain  = true;
                                echo "$tagA[quant]</td><td>";//リンクの重さ
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
                                echo "<input name='targetDelLinkID[]' value='";//削除
                                echo $articleA["article"][LinkID];
                                echo "'type='hidden' />";
                                foreach ($searchingTagA as $searchingTag) {
                                        echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
                                }
                                echo "<input name='targetDelIDFrom[]' value='$tagA[ID]'type='hidden' /><input name='searchType' value='$searchType'type='hidden' /><a href='result.php?tagID=$tagA[ID]' target='_blank'>$tagA[name]</a>";
                                echo "</form>"; 
                        }
                        
                }
        if ($isContain == false){
                echo "</td><td>";
        }
        
        echo "</td>";//正常な繰り返しのスコープ
        }
echo "</tr>";//記事描画終了
}
foreach ($table2 as $articleA){
        echo "<tr>";//タグ描画開始
        echo "<td><form action='result.php' method='post'>";
        if (is_url($articleA["tag1"][name]) == TRUE) {//返信表示
                echo "<a href='";
                echo $articleA["tag1"][name];
                echo "' target='_blank'>";
                echo $articleA["tag1"][name];
                echo "</a>";
        } else {
                echo "<a href='tagDetale.php?tagID=";
                echo $articleA["tag1"][ID];
                echo "' target='_blank'>";
                echo $articleA["tag1"][name];
                echo "</a>";
        }
        echo "<form action='result.php' method='post'>";//記事削除フォーム開始
        echo "<input value='記事の削除' type='submit' name='articleReply'>";//ボタン
        foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
        }
        foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                echo "<input name='targetDelIDFrom[]' value='$searchingTag[ID]'type='hidden' />";
        }
        echo "<input name='targetDelLinkID[]' value='";
        echo $articleA["tag1"][LinkID];
        echo "' type='hidden' />";//返信記事ID取得
        echo "</form>";//記事削除フォーム終了

        echo "<div onClick='toggleShow(this);'>";
        echo "編集";
        echo "</div>";
        echo "<div id='HSfield' style='display: none;'>";
        echo "<div id='viewMainTag' onClick='editArticle();' ><input value='編集' type='submit' name='Edit'></div>";
        echo "<div id='editMainTag'><input name='articleEdit' value='";
        echo $articleA["tag1"][name];
        echo "' style='visible' onChange='changeMainTag();' onSubmit='submitMainTag(); return true;' /></div>";
        echo "<input name='articleID' value='";
        echo $articleA["tag1"][ID];
        echo "' type='hidden' />";
        foreach ($searchingTagA as $searchingTag) {
                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
        }
        echo "<form action='result.php' method='post'>";
        foreach ($searchingTagA as $searchingTag) {
                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
        }
        echo "<input name='replyID' value='";
        echo $articleA["tag1"][ID];
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
                        echo "<a href='tagDetale.php?tagID=$Reply[ID]' target='_blank'>$Reply[name]</a>";
                        }
                        echo "<form action='result.php' method='post'>";//返信削除フォーム開始
                        echo "<input value='返信の削除' type='submit' name='articleReply'>";//ボタン
                        foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                                echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
                        }
                        echo "<input name='targetDelLinkID[]' value='";
                        echo $Reply[LinkID];
                        echo "' type='hidden' />";//返信記事ID取得
                        /*echo "<input name='targetDelIDTo[]' value='";
                        echo $Reply[ID];
                        echo "' type='hidden' />";*///記事ID取得
                        echo "</form>";//返信削除フォーム終了
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
        echo $articleA["tag1"][ID];
        echo "'type='hidden' />";
        echo "</form>";
        echo "<div onClick='toggleShow(this);'>";
        echo "コメント記事";
        echo "</div>";
        echo "<div id='HSfield' style='display: none;'>";
                echo "<form action='result.php' method='post'>";//返信フォーム開始
                echo "<input value='記事への返信' type='submit' name='articleReply'>";//ボタン
                echo "<input name='articleReply'/>";
                foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                        echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
                }
                echo "<input name='articleID' value='";
                echo $articleA["tag1"][ID];
                echo "' type='hidden' />";//記事ID取得
                echo "</form>";//返信フォーム終了
        echo "</div>";/*
                echo "<div onClick='toggleShow(this);'>";
        echo "コメントタグ";
        echo "</div>";
        echo "<div id='HSfield' style='display: none;'>";
                echo "<form action='result.php' method='post'>";//返信フォーム開始
                echo "<input value='記事への返信タグ' type='submit' name='articleReply'>";//ボタン
                echo "<input name='ReplyTag'/>";
                foreach ($searchingTagA as $searchingTag) {//使用中の検索ID取得
                        echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
                }
                echo "<input name='targetID' value='";
                echo $articleA["tag1"][ID];
                echo "' type='hidden' />";//記事ID取得
                echo "</form>";//返信フォーム終了
        echo "</div>";*/
        echo "</td>";
        //リンクの重さ
        foreach ($taghash as $key => $tagValue){//タグの数だけ回す
                echo "<td>";
                $isContain = false;
                foreach ($articleA["tag"] as $tagA){//各タグの列２つ分について全検索タグと比較このループで一回も一致しなかったら
                        if ($key == $tagA[ID]){//キーが当該IDと一致したら
                                $isContain  = true;
                                echo "$tagA[quant]</td><td>";//リンクの重さ
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
                                echo "<input name='targetDelLinkID[]' value='";//削除
                                echo $articleA["tag1"][LinkID];
                                echo "'type='hidden' />";
                                foreach ($searchingTagA as $searchingTag) {
                                        echo "<input name='tagIDList[]' value='$searchingTag[ID]'type='hidden' />";
                                }
                                echo "<input name='targetDelIDFrom[]' value='$tagA[ID]'type='hidden' /><input name='searchType' value='$searchType'type='hidden' /><a href='result.php?tagID=$tagA[ID]' target='_blank'>$tagA[name]</a>";
                                echo "</form>"; 
                        }
                        
                }
        if ($isContain == false){
                echo "</td><td>";
        }
        echo "</td>";//正常な繰り返しのスコープ
        }
echo "</tr>";
} 
?>
</tbody>
</table>
</body>
</html>