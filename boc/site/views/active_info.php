<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
<div id="main">
    <?php //include_once VIEWS.'inc/header.php'; ?>
<div class="scroll-main bgededed">
    <div class="video-info active-info">
        <div class="info">
            <div class="tit">
                <p><?php echo $_GET['language'] && $_GET['language'] == 'ZH' ? $pro['title'] : $pro[$_GET['language'].'_title']?></p>
            </div>
            <div class="time">
                <p>更新时间<?php echo date('Y-m-d',$pro['timeline'])?> <span class="line"></span> <?php echo $pro['click']?>次浏览</p>
            </div>
            <div class="con">
                <?php echo $pro[$_GET['language'].'_content']?>
            </div>
            <div class="page">
                <?php if(isset($pro['prev_id']) && $pro['prev_id']) {?>
                    <a href="<?php echo site_url('app/news?id='.$pro['prev_id'].'&token='.$_GET['token'].'&language='.$_GET['language'])?>" title="" class="box">
                        <p><i></i>上一篇：<?php echo $pro['prev_title']?></p><i class="icon"></i>
                    </a>
                <?php } else {?>
                    <a class="box">
                        <p><i></i>暂无</p>
                    </a>
                <?php }?>
                <?php if(isset($pro['next_id']) && $pro['next_id']) {?>
                    <a href="<?php echo site_url('app/news?id='.$pro['next_id'].'&token='.$_GET['token'].'&language='.$_GET['language'])?>" title="<?php echo $pro['next_title']?>" class="box">
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
    <div class="recommend-news">
        <div class="title">
            <p>推荐新闻</p>
        </div>
        <div class="list f-cb">
            <ul>
                <?php foreach($news as $val) {?>
                    <li>
                        <a href="" title="" class="box f-cb">
                            <div class="img">
                                <img src="<?php echo $val['photo']['url']; ?>" width="100%" height="100%" alt="">
                            </div>
                            <div class="text">
                                <h3><?php echo $_GET['language'] && $_GET['language'] == 'ZH' ? $val['title'] : $val[$_GET['language'].'_title']?></h3>
                                <div class="bot f-cb">
                                    <p><?php echo date('Y-m-d',$val['timeline'])?></p>
                                    <p class="eye"><?php echo $val['click']?></p>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php }?>
            </ul>
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
?>
<script>
$(function(){
	

})
</script>
</body>
</html>