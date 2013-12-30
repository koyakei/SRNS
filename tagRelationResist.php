<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Tag search relarion resister</title>
</head>
<body>
<?php
ini_set( 'display_errors', 1 );
require_once("cmn/debug.php");
require_once("cmn/utils.php");
require_once("cmn/specialTagIDList.php");
$addTagName = $_REQUEST['addTagName'];//追加するタグ名
$targetTagIDFrom = $_REQUEST['targetTagIDFrom'];//親になるタグのID
$targetArticleIDTo = $_REQUEST['targetArticleIDTo'];//タグを関連付ける記事I
$pdo = db_open();
//サジェスト親子関係存在確認
$sql = "SELECT `Tag`.*  , `userP`.`name` AS userProfile FROM `Tag` AS userP ,`User_TBL` INNER JOIN `Tag` ON `User_TBL` . `profileID` = `Tag` . `owner`   WHERE  `Tag` .`name` LIKE  '$addTagName' AND `userP`.`ID` = `User_TBL` . `profileID`";
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
print_r ($tagA);//ここに複数のタグを入れる
if ($tagA == null) {
	echo "タグを登録してください";
} else {
	echo "親子関係を調査します";
	foreach ($tagA as $eachTag){
		echo "<br>当該タグID";
		print_r ($eachTag[ID]);//既存のタグのID
		$childTagID = $eachTag[ID];
		$sql = "SELECT  `tagLink`.`LFrom` AS TLFROM, `Tag` . * FROM  `LINK` INNER JOIN  `LINK` AS tagLink ON  `LINK`.`ID` = `tagLink`.`LTo`, `Tag`  WHERE  `LINK`.`LFrom` =$targetTagIDFrom AND `tagLink`.`LFrom` =$tagSSugID  AND `Tag` . `ID` = `LINK` . `LTo` AND `LINK`.`LTo`  =  $childTagID;"; //親タグと当該タグのリンク重複を調べる
		$relationSQL = $pdo->query($sql);
		$h = 0;
		while ($row = $relationSQL->fetch()) {
			$replyName = htmlspecialchars($row['name']); 
			$replyID = htmlspecialchars($row['ID']);
			$ownerID = htmlspecialchars($row['owner']);
			$CreatedTime = htmlspecialchars($row['Created_time']);
			$relationA = array(
			'name' => $replyName,
			'owner' => $ownerID,
			'CreatedTime' => $CreatedTime,
			'ID' => $replyID
			);
			$relation[$h] = $relationA;
			$h++;
		}
		print_r ($relation);

		if ($relation == null){ //既存のタグと重複していなかったら
			echo "親子関係が存在していません";
			//親タグと子供タグのリンクを作成
			$pdo->beginTransaction();
			$sql = "INSERT INTO  `db0tagplus`.`LINK` (
			`ID` ,
			`LFrom` ,
			`LTo` ,
			`quant` ,
			`owner`
			)
			VALUES (
			NULL ,  '$targetTagIDFrom',  '$childTagID',  '$ownerID',  '1'
			);";
			$pdo->exec($sql);
			$lastAIID = $pdo->lastInsertId('ID');
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
			NULL ,  '$tagSSugID',  '$lastAIID',  '1',  '$ownerID', NOW( )
			);";
			$pdo->exec($sql); 
			$pdo->commit();
		} else {
			echo "親子関係がすでに存在します";	
		}
		
	}		
}
/*

//検索関係が子供である場合重複していないか確かめる
$sql = "SELECT `Tag`.*  , `userP`.`name` AS userProfile FROM `Tag` AS userP ,`User_TBL` INNER JOIN `Tag` ON `User_TBL` . `profileID` = `Tag` . `owner`   WHERE  `Tag` .`name` LIKE  '$addTagName' AND `userP`.`ID` = `User_TBL` . `profileID`";
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
print_r ($tagA);//ここに複数のタグを入れる

if ($tagA == null) {//既存のタグが存在しなかったら、新規追加
	echo "新規追加";
	//タグを追加する
	$pdo->beginTransaction();
	 $sql = "INSERT INTO  `db0tagplus`.`Tag` (
	`ID` ,
	`name` ,
	`owner`
	)
	VALUES (
	NULL ,  '$addTagName', '1'
	);";
	$pdo->exec($sql); 
	$lastAIID = $pdo->lastInsertId('ID');//新規に追加したタグIDを
	$pdo->commit();
	print_r ($lastAIID);
	$pdo->beginTransaction();//既存の記事とタグのリンク作成
	 $sql = "INSERT INTO  `db0tagplus`.`LINK` (
	`ID` ,
	`LFrom` ,
	`LTo` ,
	`quant` ,
	`owner`
	)
	VALUES (
	NULL ,  '$lastAIID',  '$targetArticleIDTo',  '1',  '1'
	);";
	$pdo->exec($sql); $pdo->commit(); 
	//親タグと子供タグのリンクを作成
	$pdo->beginTransaction();
	 $sql = "INSERT INTO  `db0tagplus`.`LINK` (
	`ID` ,
	`LFrom` ,
	`LTo` ,
	`quant` ,
	`owner`
	)
	VALUES (
	NULL ,  '$targetTagIDFrom',  '$lastAIID',  '1',  '1'
	);";
	$pdo->exec($sql);
	$lastAIID = $pdo->lastInsertId('ID');
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
	NULL ,  '$tagSSugID',  '$lastAIID',  '1',  '$ownerID', NOW( )
	);";
	$pdo->exec($sql); 
	$pdo->commit();
} else {
echo "関係追加";
	//既存のタグがすでに当該記事にリンク付されているのか調べる
	foreach ($tagA as $eachTag){
		echo "<br>当該タグID";
		print_r ($eachTag[ID]);//既存のタグのID
		$From = $eachTag[ID];
		$sql = "SELECT `LINK` . `ID` From `LINK`  WHERE `LINK` .`LFrom` = $eachTag[ID] AND `LINK` .`LTo` = $targetArticleIDTo;"; //当該記事と当該タグのリンクを調べる
		$existingLink = $pdo->query($sql);
		$i = 0; 
			while ($row = $existingLink->fetch()) {
				$linkID = htmlspecialchars($row['ID']);
				$Link = array(
					'ID' => $linkID
				);
				$LinkA[$i] = $Link;
				$i++;
			}
		print_r ($LinkA);

		if ($LinkA == null){ //既存のタグと重複していなかったら
			echo "重複していない";
			echo "リンクFrom追加　$From";
			$pdo->beginTransaction();
			$sql = "INSERT INTO  `db0tagplus`.`LINK` (
			 `ID` ,
			 `LFrom` ,
			 `LTo` ,
			 `quant` ,
			 `owner`
			)
			VALUES (
			NULL ,  '$From',  '$targetArticleIDTo',  '1',  '1'
			);";
			$pdo->exec($sql);
			$lastAIID = $pdo->lastInsertId('ID');
			$pdo->commit();
			//親タグと子供タグのリンクを作成
			$pdo->beginTransaction();
			 $sql = "INSERT INTO  `db0tagplus`.`LINK` (
			`ID` ,
			`LFrom` ,
			`LTo` ,
			`quant` ,
			`owner`
			)
			VALUES (
			NULL ,  '$targetTagIDFrom',  '$lastAIID',  '1',  '1'
			);";
			$pdo->exec($sql);
			$lastAIID = $pdo->lastInsertId('ID');
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
			NULL ,  '$tagSSugID',  '$lastAIID',  '1',  '$ownerID', NOW( )
			);";
			$pdo->exec($sql); 
			$pdo->commit();
		} else {
		//タグ付けが重複している場合親子関係だけを追加
			
		}
	}
}
*/
?>
</body>
/*</html>