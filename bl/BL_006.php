<?php

$error_message = "";
if (isset($_POST["btnSearch"])) {
  if($_POST["txtStr"] != "") {

    $sql = PS006_02_S01($_SESSION["PROFILE_ID"], $_POST["txtStr"]);

    $dbo = db_open();
    $result = mysql_query($sql);
    db_close($dbo);

    $cnt = 0;
    while ($row = mysql_fetch_assoc($result)) {
      $rows[$cnt][0] = $row['tag_id'];
      $rows[$cnt][1] = $row['t_name'];
      $rows[$cnt][2] = $row['s_price'];

      $cnt++;
    }

    $_SESSION["TAG_LIST"] = $rows;
    $_SESSION["TAG_LIST_CNT"] = $cnt;

    header("Location: {$URL_ROOT}/PS010.php");
  }else{
    $error_message = "ŒŸõ•¶Žš‚ª“ü—Í‚³‚ê‚Ä‚¢‚Ü‚¹‚ñB";
  }
}else if (isset($_POST["btnSetting"])) {
  header("Location: {$URL_ROOT}/PS021.php");
} else {

  $sql = PS000_03_S01($_SESSION["PROFILE_ID"], '0');
  
  $dbo = db_open();
  $result = mysql_query($sql);
  db_close($dbo);

  $cnt = 0;
  while ($row = mysql_fetch_assoc($result)) {
    $rows[$cnt][0] = $row['preset_id'];
    $rows[$cnt][1] = $row['t_name'];

    $cnt++;
  }
}

?>
