<?php

if (isset($_POST["btnBack"])) {
  header("Location: {$URL_ROOT}/PS010.php");
}else if (isset($_POST["btnDeal"])) {
  header("Location: {$URL_ROOT}/PS024.php");
}else if (isset($_POST["btnEdit"])) {
  header("Location: {$URL_ROOT}/PS020.php");
}else if (isset($_POST["btnFollow"])) {
  header("Location: {$URL_ROOT}/PS013.php");
}else if (isset($_POST["btnPAdd"])) {
  header("Location: {$URL_ROOT}/PS016.php");
}else if (isset($_POST["btnCAdd"])) {
  header("Location: {$URL_ROOT}/PS016.php");
}else {
}

?>
