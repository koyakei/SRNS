<?php

session_start();

if (!isset($_SESSION["PROFILE_ID"])) {
  $_SESSION["ERR_MESSAGE"] = "セッションが切れました。";

  header("Location: {$URL_ROOT}/PS001.php");
}

?>
