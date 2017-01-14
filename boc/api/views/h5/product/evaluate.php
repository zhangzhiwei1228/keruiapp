<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body  class="pro">
    <?php include_once VIEWS.'h5/product/inc/product.php'; ?>
	<div class="product-chose f-cb"><a href="<?php echo site_url('h5/product/size/'.$it['id']); ?> ">选择颜色 尺寸 分类<i></i></a></div>
	 <div class="product-box">
 	<div class="btn">
 		<a href="<?php echo site_url('h5/product/picinfo/'.$it['id']); ?>">图片详情</a>
 		<i></i>
 		<a href="<?php echo site_url('h5/product/evaluate/'.$it['id']); ?>" class="cur">评价<span>(<?php echo sizeof($evaluates); ?>条)</span></a>
 	</div>
 	<div class="info">
			<ul>
            <?php if ($evaluates): ?>
            <?php foreach ($evaluates as $k => $v): ?>
				<li>
					<div class="top f-cb">
						<div class="user fl"><?php echo $v['phone'] ?></div>
						<div class="date fr"><?php echo date('Y.m.d  H:i', $v['timeline']); ?></div>
					</div>
					<div class="content"><?php echo $v['content']; ?></div>
				</li>
            <?php endforeach ?>
            <?php else: ?>
                暂时无评论
            <?php endif; ?>
			</ul>
            <?php echo $page_p; ?>
		</div>
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
