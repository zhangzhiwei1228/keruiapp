<?php
$id = isset($reg[0])?$reg[0]: show_404();
$CI->load->model('product_model', 'mproduct');
$it = $CI->mproduct->get_one($id);
if (!$it) {
    dump("404 没有找到ID为".$id."的条目。", '[ac.info]404!', 'error');
    show_404();
}
// 产品属性
$options = $CI->mproduct->getProductOption($id, false);
dump($options, 'Options');
// 产品图片
$proPhotos = list_upload($it['photo']);
// 产品评论
//分页
$page = 1;
if (isset($reg[0])) {$page = $reg[0];} else { $page = 1;}
$limit = 5;
$whereEvaluate = array('pid'=>$id);
$evaluates = $CI->mproduct->getProductEvaluates($limit,0,false,$whereEvaluate);
$count = $CI->mproduct->get_count_all($whereEvaluate, 'productevaluate');
$page_p = _pages(site_url($url_base).'/'.$it['id'].'/', $limit, $count, 5);

$plove = $CI->input->get('love');
?>
<?php if ($proPhotos): ?>
<div class="product-pic swiper-container">
	<div class="swiper-wrapper">
    <?php foreach ($proPhotos as $k => $v): ?>
    <div class="swiper-slide"><img src="<?php echo UPLOAD_URL.$v['url'].'?v='.STATIC_V; ?> "  title="<?php echo $v['title']; ?>" alt="<?php echo $v['title']; ?>" width="100%"/></div>
    <?php endforeach ?>
</div>
<div class="swiper-button-prev"></div>
<div class="swiper-button-next"></div>
</div>
<?php endif; ?>
<div class="product-info">
	<div class="tit f-cb">
		<div class="tit-con fl"><?php echo $it['title']; ?></div>
	<div class="collection fr <?php echo $plove?'cur':''; ?>"><a href="javascript:;"></a></div>
	</div>
	<div class="num">
		<span class="new">￥<?php echo $it['price'] ?></span>
		<span class="old">￥<?php echo $it['marketPrice'] ?></span>
	</div>

    <input type="hidden" id="pid" value="<?php echo $it['id'] ?>">
</div>
<script type="text/javascript">
// 收藏
$('.collection a').click(function(event) {
    event.preventDefault();
    window.location.href=API_URL+'porder/loveToggle?'+$('#pid').val();;
});
</script>
