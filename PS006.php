<?php
include("cmn/defines.php");
require_once("cmn/meta.php");
require_once("cmn/utils.php");

require_once("cmn/use_session.php");

include("sql/SQL_000_03.php");
include("sql/SQL_006.php");

include("bl/BL_006.php");

include("cmn/debug.php");


$title = "É^ÉOåüçı";
?>

<html><head><title>tagPlus</title>
</head>
<body>
<div class="wrap">
<?php
include("header.php");
?>
<p>
<form method="post" action="PS010.php"><br>
<img src="images/index/pic_tab.gif" width="320" height="35" border="0" usemap="#Map" />
<map name="Map" id="Map">
Å@<area shape="rect" coords="10,0,86,34" href="#" /><!--PS006.php-->
  <area shape="rect" coords="161,0,236,39" href="PS008.php" /><!--PS008.php-->
  <area shape="rect" coords="236,0,311,34" href="PS009.php" /><!--PS009.php-->
</map>
<input name="txtStr"><input value="åüçı" type="submit" name="btnSearch"><br>
<select>
<?php
for ($i = 0; $i < $cnt; $i++) {
  print "<option value='{$rows[$i][0]}'>{$rows[$i][1]}</option>";
}
?>
</select><br>

<p align="right"><input value="ê›íË" type="submit" name="btnSetting"></p><br>
</form>
<?php
include("footer.php");
?>
</div>
</body></html>
