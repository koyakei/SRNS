<?php
include("cmn/defines.php");
require_once("cmn/meta.php");
require_once("cmn/utils.php");

require_once("cmn/use_session.php");

include("sql/SQL_010.php");

include("bl/BL_010.php");

include("cmn/debug.php");


$title = "�^�O�ꗗ";
?>
<html><head><title></title></head>
<script type="text/javascript" src="js/g-setval.js"></script>
<body>
<div class="wrap">
<form method="post" name="hedder" action="PS010.php">
<p align="left"><input value="�߂�" type="submit" name="btnBack">
<input value="����" type="submit" name="btnApply"></p></form>
<hr size="1">
<strong>�������ʈꗗ</strong><br>

<form method="post" name="InfoList" action="PS010.php">
<table border="1" cellspacing="0" cellpadding="0" width="300" align="center">
  <tbody>
  <tr align="middle" bgcolor="#80ff80">
    <td width="65%">�^�O����</td>
    <td>���l�@</td>
    <td width="10%">�@</td>
  </tr>
  <tr>
    <td width="65%"><input value="1" type="checkbox" name="Chk_SList"></td>
    <td>�@</td>
    <td width="10%" align="center">
      <p align="center"><input style="WIDTH: 26px; HEIGHT: 21px" value="i" size="30" type="submit" name="btnInfo"></p></td></tr></tbody></table></form></div></body></html>
