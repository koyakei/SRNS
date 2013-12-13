<?php
include("cmn/defines.php");
require_once("cmn/meta.php");
require_once("cmn/utils.php");

require_once("cmn/use_session.php");

include("sql/SQL_025.php");

include("bl/BL_025.php");

include("cmn/debug.php");


$title = "æˆø—š—ğˆê——";
?>
<html>
<head><title>PS0014</title>
</head>
<body>
<form method="post" name="detailtag" action="PS025.php">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tbody>
  <tr>
    <td colspan="3" align="left">@<input value="–ß‚é" type="submit" name="btnBack"> 
  </td></tr></tbody></table></form>
<hr size="1">
<strong>æˆø—š—ğˆê——<br>
<div class="wrap">
<form method="post" name="detailtag" action="PS025.php">
<?php
for ($i = 0; $i < $cnt; $i++) {
  print '<table style="TEXT-ALIGN: left; WIDTH: 300px; MARGIN-LEFT: auto; MARGIN-RIGHT: auto" border="1" cellspacing="0" cellpadding="0">';
    print '<tbody>';
  print '<tr>';
   print '<td style="WIDTH: 90px">';
    print "{$rows[$i][1]}";
	 print '<align ="right"><input style="WIDTH: 26px; HEIGHT: 21px" value="i" size="30" type="submit" name="info"></align>'
	 print '</td>';
   print "<td style="WIDTH: 91px">{$rows[$i][1]}</td>";
   print "<td style="WIDTH: 91px">{$rows[$i][1]}</td>";
  print '</tr>';
  print '<tr>';
    print "<td style="WIDTH: 90px">{$rows[$i][1]}</td>";
    print "<td style="WIDTH: 91px">{$rows[$i][1]}</td>";
    print "<td style="WIDTH: 91px">{$rows[$i][1]}</td>";
  print '</tr>';
  print '<tr>';
    print "<td style="WIDTH: 90px">³‘øó‹µ</td>";
    print "<td style="WIDTH: 91px">Ï‚İ</td>";
    print "<td style="WIDTH: 91px">–¢Š®—¹</td></tr>";
	print '</tbody></table>';
	?>
</form>
<?php
include("footer.php");
?>
</div>
</body></html>
