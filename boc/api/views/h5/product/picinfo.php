<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="pro">
    <?php include_once VIEWS.'h5/product/inc/product.php'; ?>
	<div class="product-chose f-cb"><a href="<?php echo site_url('h5/product/size/'.$it['id']); ?> ">选择颜色 尺寸 分类<i></i></a></div>
	<div class="product-box">
	<div class="btn">
		<a href="<?php echo site_url('h5/product/picinfo/'.$it['id']); ?>" class="cur" >图片详情</a>
		<i></i>
		<a href="<?php echo site_url('h5/product/evaluate/'.$it['id']); ?>">评价<span>(<?php echo sizeof($evaluates); ?>条)</span></a>
	</div>
    <?php echo $it['content'] ?>

    <?php if (false): ?>
 	<!-- <div class="picture"><img src="<?php echo static_file('m/img/service.jpg'); ?>  " alt="" width="100%" /></div>
 	<div class="pro-productinfo">
 		<div class="pro-title"><span>商品信息</span><strong>PRODUCT INFO</strong></div>
 		<div class="con">
 			<img src="<?php echo static_file('m/img/table1.jpg'); ?>  " alt="" width="100%" />
 		</div>
 	</div>
 	<div class="pro-sizeinfo">
 		<div class="pro-title"><span>尺码信息</span><strong>SIZE INFO</strong></div>
 		<div class="con">
 			<img src="<?php echo static_file('m/img/table2.jpg'); ?>  " alt="" width="100%" />
 		</div>
 		<div class="tip">提示：左滑查看完整表格信息</div>
 	</div>
 	<div class="pro-mea">
 		<div class="pro-title"><span>测量方式</span><strong>MEASURMENT METHOD</strong></div>
 		<div class="con">
 			<img src="<?php echo static_file('m/img/table3.jpg'); ?>  " alt="" width="100%" />
 		</div>
 	</div> -->
    <?php endif; ?>
 </div>
 <div class="m-buy">
     <a href="<?php echo site_url('porder/cardAdd'); ?>" id="addCart">加入购物车</a>
     <a href="<?php echo site_url('porder/confirmNow'); ?>" id="buyNow">立即购买</a>
 </div>

<?php
    echo static_file('m/js/main.js');
    echo static_file('m/swiper/swiper.css');
    echo static_file('m/swiper/swiper.min.js');
?>
<script>
	var mySwiper = new Swiper ('.swiper-container', {
    direction: 'horizontal',
    loop: true,
    // 如果需要前进后退按钮
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev'

  })
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
