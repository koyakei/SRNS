<!--ヘッダー-->
<div class="heder">
<!--ヘッダーボタン左側-->
<!--ボタン画像一覧
ログアウトは/images/hd_btn_logout.gif
ホームに戻るは/images/hd_btn_home.gif
汎用の戻るボタンは/images/hd_btn_back.gif
それぞれの画像が入ります-->
<div class="heder_btn_l">
<a href="#"><img src="images/hd_btn_back.gif" width="60" height="30" border="0" /></a>
</div>
<!--ヘッダーボタン終了-->

<!--ヘッダーページタイトル(半角15字までOK、それ以上はカラム落ちします)-->
<div class="heder_tit"><?php print $title; ?></div>
<!--ヘッダーページタイトル終了-->

<!--ヘッダーボタン右側-->
<!--検索ホームなので空の状態です。他ページではボタンが入ります-->
<div class="heder_btn_r">
</div>
<!--ヘッダーボタン終了-->
</div>
<!--ヘッダー終了-->
<!--以下コンテンツ部分-->

<!--アカウントステータス表示-->
<div class="status">
ようこそ<?php print $_SESSION["PROFILE_NAME"]; ?>さん
</div>
<!--アカウントステータス終了-->
