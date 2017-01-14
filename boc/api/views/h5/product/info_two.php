<!DOCTYPE html>
<?php
$CI->load->model('product_model', 'mpro');
$it = $CI->mpro->get_one($reg[0]);
$photos = list_upload($it['photo']);
$price = explode('.', number_format($it['price'], 2));
?>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
    <div class="por-info">
        <div id="slideBox" class="slideBox wrap swiper-container">
           <div class="n-banner">
            <div class="swiper-wrapper">
            <?php foreach ($photos as $k => $v): ?>
              <div class="swiper-slide"><a href="#" ><img src="<?php echo UPLOAD_URL.$v['url']; ?> "></a></div>
            <?php endforeach ?>
            </div>
            <div class="hd-1"></div>
        </div>
    </div>
    <div class="por-info-title f-cb">
        <h3 class="w92"><?php echo $it['title'] ?><?php echo $it['title_sub']?'-'.$it['title_sub']:''; ?></h3>
        <h4 class="w92">¥<?php echo $price[0] ?>.<i style="font-size:15px;"><?php echo $price[1] ?></i> <em>价格 ￥<?php echo $it['marketPrice'] ?></em></h4>
        <span><em>运费：0元包邮</em><em style="margin-left:15px;">七天内无理由放心退</em></span>
    </div>
    <div class="line-box"></div>
    <div class="tab-box">
      <span><a href="<?php echo site_url('h5/product/info_one/'.$it['id']); ?> ">产品参数</a></span>
      <span><a href="<?php echo site_url('h5/product/info_two/'.$it['id']); ?>">图文详情</a></span>
    </div>
    <div class="por-info-mid w92">
      <?php echo $it['detail'] ?>
    </div>
   <div class="line-box"></div>
<?php
	echo static_file('api/js/main.js');
    echo static_file('api/swiper/swiper.min.js');
    //css
    echo static_file('api/swiper/swiper.css');
?>

<script>
$(".tab-box span a:eq(1)").addClass("ctn");
$(function(){
	var swiper = new Swiper('.n-banner', {
        pagination: '.hd-1',
        paginationClickable: true,
        spaceBetween: 0,
        centeredSlides: true,
        autoplay: 4500,
        autoplayDisableOnInteraction: false,
        });
})
</script>
</body>
</html>
