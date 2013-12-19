<html>
<head>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
//最初に表示させるアイテムの数
var news = 2;
//表示非表示のイベント発火用テキスト
hidenews = "- 表示を隠す";
shownews = "+ 残りを表示する";

//最初は非表示
$(".archive").html( shownews );
$(".news:not(:lt("+news+"))").hide();

//クリックで表示/非表示を切り替え
$(".archive").click(function (e) {
   e.preventDefault();
       if ($(".news:eq("+news+")").is(":hidden")) {
           $(".news:hidden").show();
           $(".archive").html( hidenews );
       } else {
           $(".news:not(:lt("+news+"))").hide();
           $(".archive").html( shownews );
       }
});
</script>
</head>
<body>
<div class="news">div 1</div>
<div class="news">div 2</div>
<div class="news">div 3</div>
<div class="news">div 4</div>
<div class="news">div 5</div>
<div class="news">div 6</div>

<a class="archive" href="#"></a>

</body>
</html>