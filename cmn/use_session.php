<?php

session_start();

if (!isset($_SESSION["PROFILE_ID"])) {
  $_SESSION["ERR_MESSAGE"] = "�Z�b�V�������؂�܂����B";

  header("Location: {$URL_ROOT}/PS001.php");
}

?>
