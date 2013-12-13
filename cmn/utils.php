<?php
  // DBÚ‘±
  function db_open(){
 $pdo = new PDO("mysql:host=www49.atpages.jp;dbname=db0tagplus", "tagplus",
"7JAKPuhARAKbkg");
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
