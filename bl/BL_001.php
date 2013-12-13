<?php

$error_message = "";
if (isset($_POST["btnLogin"])) {
  if($_POST["id"] != "" && $_POST["pwd"] != ""){
    $dbo = db_open();

    $sql = PS001_02_S01($_POST["id"],$_POST["pwd"]);

    $result = mysql_query($sql);

    db_close($dbo);
    $row = mysql_fetch_assoc($result);

    $_SESSION[ "PROFILE_ID" ] = $row["profile_tag_id"];
    $_SESSION[ "PROFILE_NAME" ] = $row["t_name"];

    if (get_row_count($result) != "0"){
      header("Location: {$URL_ROOT}/PS002.php");
    }else{
      $error_message = "ユーザーまたはパスワードが間違っています";
    }
    exit();
  }else{
    $error_message = "ユーザーまたはパスワードが未入力です";
  }
}

if (isset($_POST["lnk"])) {
  header("Location: {$URL_ROOT}/PS021.php");
}

?>
