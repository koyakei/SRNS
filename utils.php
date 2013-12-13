<?php
  // DBÚ‘±
  function db_open(){
    $dbo = mysql_connect('localhost', 'tagplus', '7JAKPuhARAKbkg');
    mysql_query("SET NAMES sjis",$dbo);
    $db_selected = mysql_select_db('tagplus', $dbo);

    return $dbo;
  }

  function db_close($dbo){
    mysql_close($dbo);
  }

  function get_row_count($result){
    $cnt = mysql_num_rows($result);
    if ($cnt == ""){
      $cnt = '0';
    }
    return $cnt;
  }



?>
