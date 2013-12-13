<?php
include("cmn/defines.php");
require_once("cmn/meta.php");
require_once("cmn/utils.php");

require_once("cmn/init_session.php");

include("sql/SQL_001.php");

include("bl/BL_001.php");

include("cmn/debug.php");

?>
<html><head><title>ログイン画面</title>
</head>
<script type="text/javascript" src="js/g-post.js"></script>
<body>
<div>
<form name="frm1" method="post" action="PS001.php"><img border="0" alt="" src="images/logo.gif" width="300" height="300"> <br>
<table border="0" cellspacing="0" cellpadding="0" width="300">
  <tbody>
  <tr>
    <td>　ID</td>
    <td><input name="id"></td>
  </tr>
  <tr>
    <td>　PASS</td>
    <td><input name="pwd"></td>
  </tr>
  <tr>
    <td colspan="2" align="center">　<input value="ログイン" type="submit" name="btnLogin"></td>
  </tr>
  <tr>
    <td colspan="2" align="right"><a href="#" name="lnk" onclick="return doPost(document.frm1, this.href, this.name)"><font size="2">新規登録はこちら</font></a>　</td></tr></tbody></table></form></div></body></html>
