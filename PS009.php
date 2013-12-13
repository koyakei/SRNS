<?php
include("cmn/defines.php");
require_once("cmn/meta.php");
require_once("cmn/utils.php");

require_once("cmn/use_session.php");

include("sql/SQL_000_03.php");
include("sql/SQL_009.php");

include("bl/BL_009.php");

include("cmn/debug.php");


$title = "アカウント検索";
?>
<html><head><title></title>
</head>
<body>
<div class="wrap">
<?php
include("header.php");
?>
<p>
<form method="post" action="PS013.php"><br>
<img src="images/index/pic_tab.gif" width="320" height="35" border="0" usemap="#Map" />
<map name="Map" id="Map">
　<area shape="rect" coords="10,0,86,34" href="PS006.php" /><!--PS006.php-->
  <area shape="rect" coords="161,0,236,39" href="PS008.php" /><!--PS008.php-->
  <area shape="rect" coords="236,0,311,34" href="PS009.php" /><!--PS009.php-->
</map>
<input name="txtStr"><input value="検索" type="submit" name="btnSarch"><br>
<select>
<?php
for ($i = 0; $i < $cnt; $i++) {
  print "<option value='{$rows[$i][0]}'>{$rows[$i][1]}</option>";
}
?>
</select><br>

<p align="right"><input value="設定" type="submit" name="btnSetting" ></p><br>
</form>
<?php
include("footer.php");
?>
</div>
</body></html>
