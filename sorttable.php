<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>ソートできる表を作成する「sorttable.js」</title>
<script src="js/sorttable.js" type="text/javascript"></script>
<link href="css/sitemapstyler.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/sitemapstyler.js"></script>

</head>
<body>

<table class="sortable">
<thead>
</thead>
<tbody>
<form>
<select name="都道府県">
<option value="1">北海道</option>
<option value="2" selected>東京</option>
<option value="3">沖縄</option>
</select>
</form>
<ul id="sitemap">
			<li><a href="#">First link</a>
				<ul>
					<li><a href="#">First link</a>
						<ul>
							<li><a href="#">First link</a></li>
							<li><a href="#">Second link</a></li>
							<li><a href="#">Third link</a></li>
							<li><a href="#">Fourth link</a></li>
							<li><a href="#">Fifth link</a></li>
						</ul>							
					</li>

				</ul>					
			</li>
</ul>
</tbody>
</body>
</html>