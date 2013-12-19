<html>
<head>
<link rel="stylesheet" type="text/css" href="css/tablesorter.css" />
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="js/sorttable.js" type="text/javascript"></script>
<script type="text/javascript" src="js/dragtable.js"></script>
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
-->
<script type="text/javascript">
  function toggleShow() {
    if (HSfield.style.visibility == 'hidden') {
      HSfield.style.visibility = 'visible';
    } else {
      HSfield.style.visibility = 'hidden';
    }
  }
</script>
<title>Article Detale</title>
</head>
<body>
<?php
ini_set( 'display_errors', 1 );
require_once("cmn/debug.php");
require_once("cmn/utils.php");
$ID =  $_REQUEST['ID'];//IDでタグも記事もどっちもとってくる？

if (is_array($ID)){
$whereLinkOR = "(`LINK`.`LFrom` =" . join(" OR `LINK`.`LFrom` =", $ID).")";
$whereAND = "`ID`=" . join(" AND `ID`=", $ID);
echo $whereLinkOR;
}
echo $ID;//とりあえず、記事の1
$sql = "SELECT DISTINCT `article` . * FROM  `LINK` , `article` WHERE  $ID AND `LINK`.`LTo` =  `article`.`ID` ";
?>
</body>
</html>