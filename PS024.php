<?php
include("cmn/defines.php");
require_once("cmn/meta.php");
require_once("cmn/utils.php");

require_once("cmn/use_session.php");

include("sql/SQL_024.php");

include("bl/BL_024.php");

include("cmn/debug.php");


$title = "タグ一覧";
?>
<html><head><title>PS0014</title>
</head>
<body>
<form method="post" name="detailtag" action="detailtag.php">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tbody>
  <tr>
    <td colspan="2" align="left">　<input value="戻る" type="submit" name="btnBack"> </td>
    <td align="right">　<input value="決定" type="submit" name="btnDeal" 　action="PS024.php"></td></tr></tbody></table></form>
<hr size="1">
<strong>取引情報</strong><br>
<form method="post" name="detailtag" action="detailtag.php">

<table style="TEXT-ALIGN: left; WIDTH: 300px; HEIGHT: 105px; MARGIN-LEFT: auto; MARGIN-RIGHT: auto" border="1" cellspacing="0" cellpadding="0" align="center">
  <tbody>
  <tr>
    <td style="BACKGROUND-COLOR: rgb(128,255,128); WIDTH: 109px" colSpan=3>自分</td>
  </tr>
  <tr>
    <td style="WIDTH: 75px">どこへ</td>
    <td style="WIDTH: 23px" colspan="2"><?php print "{$rows[1][0]}"; ?></td>
  </tr>
  <tr>
    <td style="WIDTH: 69px">何を</td>
    <td style="WIDTH: 23px" colspan="2">
    <select name="lstMydealtag">
		<?php
for ($i = 0; $i < $sql; $i++) {
  print "<option value='{$rows[$i][0]}'>{$rows[$i][1]}</option>";
}
?>
</select></td>
  </tr>
  <tr>
    <td style="WIDTH: 70px">価値</td>
    <td style="TEXT-ALIGN: right; WIDTH: 90px"><?php print "廃止"; ?></td>
    <td style="WIDTH: 109px" rowspan="2">承諾
    <?php if ($rows[9][0]}<input value="1" type="checkbox" name="Chk_SList">?></td>
  </tr>
  <tr>
    <td style="WIDTH: 23px; HEIGHT: 20px">枚数</td>
    <td style="TEXT-ALIGN: right; HEIGHT: 20px"><?php print "{$rows[8][0]}"; ?></td>
  </tr>
  <tr></tr></tbody></table>
<div style="TEXT-ALIGN: center"><img border="0" alt="" src="images/Deal.gif"> <br>
</div>
<div align="center">
  <table style="TEXT-ALIGN: left; WIDTH: 300px; HEIGHT: 105px; MARGIN-LEFT: auto; MARGIN-RIGHT: auto" border="1" cellspacing="0" cellpadding="0" align="center">
    <tbody>
      <tr>
        <td style="BACKGROUND-COLOR: rgb(128,255,128); WIDTH: 109px" colSpan=3><?php print "{$rows[11][0]}"; ?>相手id</td>
        </tr>
      <tr>
        <td style="WIDTH: 75px">付与対象</td>
        <td style="WIDTH: 23px" colspan="2"><select name="lstMydealtag">
		<?php
for ($i = 0; $i < $cnt; $i++) {
  print "<option value='{$rows[$i][0]}'>{$rows[$i][1]}</option>";
}
?>
</select> </td>
        </tr>
      <tr>
        <td style="WIDTH: 69px">取引タグ</td>
        <td style="WIDTH: 23px" colspan="2">Bタグ </td>
        </tr>
      <tr>
        <td style="WIDTH: 70px">価値</td>
        <td style="TEXT-ALIGN: right; WIDTH: 90px">123</td>
        <td style="WIDTH: 109px" rowspan="2">承諾<input value="1" type="checkbox" name="Chk_SList"></td>
        </tr>
      <tr>
        <td style="WIDTH: 23px; HEIGHT: 20px">枚数</td>
        <td style="TEXT-ALIGN: right; HEIGHT: 20px">123</td>
        </tr>
    <tr></tr></tbody></table>
</div>
</form></body></html>
