<?php

    // このディレクトリのフルパス取得する
    $hd = realpath(dirname(__FILE__));


    // ホームディレクトリをHOME_DIRとする
    define(HOME_DIR, $hd);


    // SmartyクラスのあるディレクトリをCLASS_DIRとする
    define(CMN_DIR, $hd."/cmn/");


    // ViewクラスのあるディレクトリをVIEWCLASS_DIRとする
    define(SQL_DIR, $hd."/sql/");
?>