<?php
include("cmn/defines.php");
require_once("cmn/meta.php");
require_once("cmn/utils.php");

require_once("cmn/use_session.php");

include("sql/SQL_013.php");

include("bl/BL_013.php");

include("cmn/debug.php");


$title = "タグ一覧";
?>
<html><head><title></title></head>
</script>
<body>
<div class="wrap">
<?php
include("header.php");
?>
<form method="post" name="hedder" action="PS013.php">
<p align="left"><input value="戻る" type="submit" name="btnBack">
</form>
<hr size="1">
<strong>検索結果一覧</strong><br>

<form method="post" name="InfoList" action="PS010.php">
<table border="1" cellspacing="0" cellpadding="0" width="300" align="center">
  <tbody>
  <tr align="middle" bgcolor="#80ff80">
    <td width="65%">アカウント名</td>
    <td>所持HEY</td>
    <td width="10%">　</td>
  </tr>
<?php
for ($i = 0; $i < $cnt; $i++) {
  print '<tr>';
  if (false) {
    print "<td width=\"65%\"><input value=\"1\" type=\"checkbox\" name=\"Chk_SList\">{$rows[$i][1]}</td>";
  }else {
    print "<td width=\"65%\">{$rows[$i][1]}</td>";
  }
  print "<td>{$rows[$i][2]}</td>";
  print '<td width="10%" align="center">';
  print '<p align="center"><input style="WIDTH: 26px; HEIGHT: 21px" value="i" size="30" type="submit"';
  print " onClick=\"setVal(document.InfoList.rowIndex,$i);\"";
  print ' name="btnInfo"></p>';
  print '</td>';
  print '</tr>';
}
?>
  </tbody>
</table>
<input type="hidden" name="rowIndex" value=""/>
</form>
<?php
include("footer.php");
?>
</div>
</body></html>
