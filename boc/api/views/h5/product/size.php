<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="body-size pro">
    <?php include_once VIEWS.'h5/product/inc/product.php'; ?>
    <div class="product-chose f-cb"><a href="<?php echo current_url(); ?> ">选择
        <?php foreach ($options as $k => $v): ?>
            <?php echo $v['title'] ?>
        <?php endforeach; ?><i></i></a></div>
    <div class="size-box">
    <?php if ($options): ?>
    <?php foreach ($options as $k => $v): ?>
        <?php if ($v['more']): ?>
        <div class="size f-cb options" id="<?php echo 'option_'.$v['optionId']; ?>">
            <?php $moreCounter = 0; ?>
            <?php foreach ($v['more'] as $ks => $vs): ?>
            <div class="style fl <?php echo $moreCounter==0?'cur':''; ?>" data-val="<?php echo $ks ?>"><?php echo $vs; ?></div>
            <?php $moreCounter++ ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    <?php endforeach ?>
    <?php endif; ?>
 	<div class="num">
 		<div class="num-input">
 			<a href="javascript:;" class="jian"></a>
 			<input type="text" id="num"/>
 			<a href="javascript:;" class="jia"></a>
 		</div>
 	</div>
 </div>
<div class="submit">
    <input type="submit" id="submit" value="确定" />
</div>
<?php
    echo static_file('m/js/main.js');
    echo static_file('m/js/base64.js');
    echo static_file('m/swiper/swiper.css');
    echo static_file('m/swiper/swiper.min.js');
?>
<script>


// 点击确定事件
$('#submit').click(function(event) {
    event.preventDefault();
    var data = {};
    $('.options').each(function(index, el) {
        var optionNow = $(this).attr('id');
        data[optionNow] = $('#'+optionNow+' div.cur').attr('data-val');
    });

    data['num'] = $('#num').val();
    data['pid'] = $('#pid').val();

    window.location.href=API_URL+'product/confirm?'+jsonEncry(data);
});

function getJsonObjLength(jsonObj) {
        var Length = 0;
        for (var item in jsonObj) {
            Length++;
        }
        return Length;
}

function jsonEncry(data) {
    var dataNow = 0;
    var dataLength = getJsonObjLength(data);

    var jStr = "{ ";
    for(var item in data){
        dataNow++;
        if (dataNow < dataLength) {
        jStr += "\""+item+"\":\""+data[item]+"\",";
        } else {
        jStr += "\""+item+"\":\""+data[item]+"\"";
        }
    }
    jStr += " }";

    jStr = base64_encode(jStr).replace(/\=/g, '.').replace(/\+/g, '*').replace(/\//g, '-');

    return jStr;
}

$('.options').each(function(index, el) {
$(this).find('.style').click(function(event) {
    $(this).parent().find('.style').removeClass('cur');
    $(this).addClass('cur');
});
});

if ($('.num input[type="text"]').val()=='') {
    $('.num input[type="text"]').val(1)
}

$('.jian').click(function(event) {
	var num=parseInt($('.num input[type="text"]').val());
	if (num>=1) {
		num=num-1;
		$('.num input[type="text"]').val(num);
	}

	$(this).css('background', 'url("<?php echo static_file('m/img/hjian.jpg'); ?> ") no-repeat center 16px' );
	$(this).css('background-size', '15px 15px');
	$('.jia').css('background', 'url("<?php echo static_file('m/img/jia.jpg'); ?> ") no-repeat center 16px' );
	$('.jia').css('background-size', '15px 15px');
});

$('.jia').click(function(event) {
	var num=parseInt($('.num input[type="text"]').val());
		num=num+1;
	$('.num input[type="text"]').val(num);
	$(this).css('background', 'url("<?php echo static_file('m/img/hjia.jpg'); ?> ") no-repeat center 16px' );
	$(this).css('background-size', '15px 15px');
	$('.jian').css('background', 'url("<?php echo static_file('m/img/jian.jpg'); ?> ") no-repeat center 16px' );
	$('.jian').css('background-size', '15px 15px');
});

$('.color a').click(function(event) {
		$('.color a').removeClass('cur');
		$(this).addClass('cur');
	});

var mySwiper = new Swiper ('.swiper-container', {
    direction: 'horizontal',
    loop: true,
    // 如果需要前进后退按钮
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev'
});

var ProImg = new Array();

for (var i = 0; i < $(".swiper-wrapper .swiper-slide").length; i++) {
	ProImg[i] = $(".swiper-wrapper .swiper-slide").eq(i).find("img").attr("src")
};
_PreLoadImg([
	ProImg
],function(){
	var height=$(".swiper-wrapper .swiper-slide").eq(1).find("img").height()
	$('.swiper-container').height(height);
})

</script>
</body>
</html>
