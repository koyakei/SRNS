<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Tag Resister</title>
</head>
<body>
<?php
ini_set( 'display_errors', 1 );
require_once("cmn/debug.php");
require_once("cmn/utils.php");
$tagAdd = $_REQUEST['tagAdd']; //追加したいタグ名
$targetIDTo = $_REQUEST['targetIDTo'];
$pdo = db_open();
$sql = "SELECT `Tag`.*  , `userP`.`name` AS userProfile FROM `Tag` AS userP ,`User_TBL` INNER JOIN `Tag` ON `User_TBL` . `profileID` = `Tag` . `owner`   WHERE  `Tag` .`name` LIKE  '$tagAdd' AND `userP`.`ID` = `User_TBL` . `profileID`";
$tagIDfinding = $pdo->query($sql);//既存のタグを探す
$i = 0;
 while ($row = $tagIDfinding->fetch()) {
	$name = htmlspecialchars($row['name']);
	$ID = htmlspecialchars($row['ID']);
	$owner = htmlspecialchars($row['owner']);
	$userProfile = htmlspecialchars($row['userProfile']);
	//print_r ($owner);
	echo "tes";
	$Tag = array(
		'name' => $name,
		'ID' => $ID,
		'ownerID' => $owner,
		'userProfile' => $userProfile
	);

	$tagA[$i] = $Tag; 
	$i++;
	
	}
print_r ($tagA);

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
	NULL ,  '$tagAdd', '1'
	);";
	$pdo->exec($sql); 
	$lastAIID = $pdo->lastInsertId('ID');
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
	NULL ,  '$lastAIID',  '$targetIDTo',  '1',  '1'
	);";
	$pdo->exec($sql); $pdo->commit(); 
} else {
echo "関係追加";
	//既存のタグがすでに当該記事にリンク付されているのか調べる
	foreach ($tagA as $eachTag){
		echo "<br>当該タグID";
		print_r ($eachTag[ID]);//既存のタグのID
		$From = $eachTag[ID];
		$sql = "SELECT `LINK` . `ID` From `LINK`  WHERE `LINK` .`LFrom` = $eachTag[ID] AND `LINK` .`LTo` = $targetIDTo;"; 
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

		if ($LinkA == null){
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
			NULL ,  '$From',  '$targetIDTo',  '1',  '1'
			);";
			$pdo->exec($sql); $pdo->commit();
		} else {
		//重複している場合
			echo "重複している";
		}
	}
}

?>
</body>
</html>