<?php
if (isset($_POST["btnBack"])) {
  header("Location: {$URL_ROOT}/PS009.php");
}else if (isset($_POST["btnbtnApply"])) {
  header("Location: {$URL_ROOT}/PS021.php");
}else if (isset($_POST["btnInfo"])) {
//  header("Location: {$URL_ROOT}/PS017.php");
}else {
   $rows = $_SESSION["TAG_LIST"];
   $cnt = $_SESSION["TAG_LIST_CNT"];

}

?>
