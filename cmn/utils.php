<?php
  // DBÚ‘±
  function db_open(){
 $pdo = new PDO("mysql:localhost;dbname=db0tagplus", "root",
"");
$pdo->query("use db0tagplus;");
    return $pdo;
  }

  //function db_close($pdo){
  //  mysql_close($pdo);
  //}

  function get_row_count($result){
    $cnt = mysql_num_rows($result);
    if ($cnt == ""){
      $cnt = '0';
    }
    return $cnt;
  }



?>
