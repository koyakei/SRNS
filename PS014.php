<?php
include("cmn/defines.php");
require_once("cmn/meta.php");
require_once("cmn/utils.php");

require_once("cmn/use_session.php");

include("sql/SQL_014.php");

include("bl/BL_014.php");

include("cmn/debug.php");

$title = "タグ詳細画面";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head><title>PS0014</title>
<meta content="text/html; charset=shift_jis" http-equiv="Content-Type">
<meta name="GENERATOR" content="MSHTML 8.00.6001.19393"></head>
<body>
<form method="post" name="detailtag" action="PS014.php">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tbody>
  <tr style="WIDTH: 269px">
    <td  colspan="2" align="left"><input value="戻る" type="submit" name="btnBack"> </td>
    <td style="WIDTH: 40px"><input value="取引" type="submit" name="btnDeal" align="right"></td>
    <td align="right">　<input value="変更" type="submit" name="btnEdit"></td></tr></tbody></table></form>
<hr size="1">

<form method="post" name="detailtag" action="PS014.php">
<table style="TEXT-ALIGN: left; WIDTH: 300px; HEIGHT: 150px; MARGIN-LEFT: auto; MARGIN-RIGHT: auto" border="1" cellspacing="0" cellpadding="0">
  <tbody>
  <tr>
    <td style="BACKGROUND-COLOR: rgb(128,255,128); WIDTH: 61px" colSpan=5>
      <p style="WIDTH: 45px">タグ名</p></td>
    <td style="TEXT-ALIGN: right"><input value="フォロー" type="submit" name="btnFollow"></td>
  </tr>
  <tr>
    <td style="WIDTH: 49px">価値</td>
    <td style="WIDTH: 34px"><input value="1" type="checkbox" name="Chk_TVa"> </td>
    <td style="WIDTH: 67px">変更可</td>
    <td style="WIDTH: 61px" colspan="3"></td>
  </tr>
  <tr>
    <td style="WIDTH: 49px">枚数</td>
    <td style="WIDTH: 34px"><input value="1" type="checkbox" name="Chk_TNum"> </td>
    <td style="WIDTH: 67px">変更可</td>
    <td style="WIDTH: 61px" colspan="3">　</td>
  </tr>
  <tr>
    <td style="WIDTH: 34px" colspan="2">交換期間</td>
    <td style="WIDTH: 67px">開始</td>
    <td style="WIDTH: 61px" colspan="3" align="right">　</td>
  </tr>
  <tr>
    <td style="WIDTH: 34px" colspan="2">　</td>
    <td style="WIDTH: 67px">終了</td>
    <td style="WIDTH: 61px" colspan="3" align="right"><br>
</td>
  </tr>
  <tr>
    <td style="WIDTH: 61px; HEIGHT: 80px" colspan="6">　終了<br>
</td>
  </tr>
  <tr>
    <td style="WIDTH: 34px; HEIGHT: 0px" colspan="2">オーナー</td>
    <td style="TEXT-ALIGN: right; WIDTH: 61px; HEIGHT: 0px" colspan="4">ユーザー名前<br>
</td>
  </tr>
  <tr>
    <td style="WIDTH: 34px; HEIGHT: 0px" colspan="2">親タグ</td>
    <td style="WIDTH: 61px; HEIGHT: 0px" colspan="2"><input value="追加" type="submit" name="btnPAdd"><br>
</td>
    <td style="WIDTH: 67px">子タグ</td>
    <td style="TEXT-ALIGN: right; WIDTH: 58px; HEIGHT: 0px"><input value="追加" type="submit" name="btnCAdd"></td>
  </tr>
  <tr>
    <td style="WIDTH: 67px; HEIGHT: 0px" colspan="3"><input id="Pa" value="Pallow" CHECKED type="radio" name="P"> <label for="Pa">許可 </label><input id="Pp" value="Pprohib" type="radio" name="P"> <label for="Pp">禁止 </label></td>
    <td style="WIDTH: 61px; HEIGHT: 0px" colspan="3" align="right"><input id="Ca" value="Callow" CHECKED type="radio" name="C"> <label for="Ca">許可 </label><input id="Cp" value="Cprohib" type="radio" name="C"> <label for="Cp">禁止 
  </label></td></tr></tbody></table></form></body></html>
