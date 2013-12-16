<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>ソートできる表を作成する「sorttable.js」</title>
<script src="js/sorttable.js" type="text/javascript"></script>
</head>
<body>

<table class="sortable">
<?php
echo "<thead>";
?>
  <tr><th>Person</th><th>Monthly pay</th><th class="sorttable_nosort">NoSort</th></tr>
</thead>
<tbody>
  <tr><td sorttable_customkey="1">Jan Molby</td><td>￡12,000</td><td>No.1</td></tr>
  <tr><td>Steve Nicol</td><td>￡8,500</td><td>N/A</td></tr>
  <tr><td>Steve McMahon</td><td>￡9,200</td><td>N/A</td></tr>
  <tr><td>John Barnes</td><td>￡15,300</td><td>N/A</td></tr>
</tbody>
</body>
</html>