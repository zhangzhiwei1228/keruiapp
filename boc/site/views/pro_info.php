<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
<div id="main">
    <?php //include_once VIEWS.'inc/header.php'; ?>
<div class="scroll-main">
    <div class="pro-info">
    	<div class="title">
    		<h3><?php echo $_GET['language'] && $_GET['language'] == 'ZH' ? $pro['title'] : $pro[$_GET['language'].'_title']?></h3>
    		<p>更新时间<?php echo date('Y-m-d',$pro['timeline'])?> <span class="line"></span> <?php echo $pro['click']?>次浏览</p>
    	</div>
    	<div class="content" id="Gallery">
    		<?php echo $pro[$_GET['language'].'_content']?>
    	</div>
    	<div class="page">
			<?php if(isset($pro['prev_id']) && $pro['prev_id']) {?>
    		<a href="<?php echo site_url('app/proInfo?id='.$pro['prev_id'].'&token='.$_GET['token'].'&language='.$_GET['language'])?>" title="" class="box">
    			<p><i></i>上一篇：<?php echo $pro['prev_title']?></p><i class="icon"></i>
    		</a>
			<?php } else {?>
				<a class="box">
					<p><i></i>暂无</p>
				</a>
			<?php }?>
			<?php if(isset($pro['next_id']) && $pro['next_id']) {?>
    		<a href="<?php echo site_url('app/proInfo?id='.$pro['next_id'].'&token='.$_GET['token'].'&language='.$_GET['language'])?>" title="<?php echo $pro['next_title']?>" class="box">
    			<p><i></i>下一篇：<?php echo $pro['next_title']?></p><i class="icon"></i>
    		</a>
			<?php } else {?>
				<a class="box">
					<p><i></i>暂无</p>
				</a>
			<?php }?>
    	</div>
    </div>
</div>
	<!-- <div class="pro-footer">
		<input type="text" class="text" placeholder="写评论...">
		<div class="save"></div>
		<div class="load-btn"></div>
	</div> -->
    <?php //include_once VIEWS.'inc/footer.php'; ?>
</div>
<?php
	echo static_file('m/js/main.js');
	echo static_file('m/photoswipe/photoswipe.css');
	echo static_file('m/photoswipe/klass.min.js');
	echo static_file('m/photoswipe/code.photoswipe.jquery-3.0.5.min.js');
?>
<script>
$(function(){
	if($("#Gallery img").length>=1){
		var myPhotoSwipe = $("#Gallery img").photoSwipe({ 
	    	enableMouseWheel: false, 
	    	enableKeyboard: false,
	    	getImageSource:function(el){
	    		return el.getAttribute('src');
	    	}
	    });
	}

	// 评论
	$('.pro-footer').keydown(function(event){
		var e = event || window.event || arguments.callee.caller.arguments[0];
		if(e && e.keyCode==13){
			//要做的事情

		}
	})

	// 收藏
	$('.pro-footer .save').click(function(){
		if($(this).hasClass('cur')){
			$(this).removeClass('cur');
		}else{
			$(this).addClass('cur');
		}
	})
	

    /*applicationCache.addEventListener("updateready",function(){
        // 手工更新本地缓存
        applicationCache.swapCache();
        // 重载页面
        location.reload();
    },true);*/
})
</script>
</body>
</html>