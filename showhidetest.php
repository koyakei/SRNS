<html>
<head>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
//�ŏ��ɕ\��������A�C�e���̐�
var news = 2;
//�\����\���̃C�x���g���Ηp�e�L�X�g
hidenews = "- �\�����B��";
shownews = "+ �c���\������";

//�ŏ��͔�\��
$(".archive").html( shownews );
$(".news:not(:lt("+news+"))").hide();

//�N���b�N�ŕ\��/��\����؂�ւ�
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