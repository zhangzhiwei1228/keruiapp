<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
<div id="main">
    <?php //include_once VIEWS.'inc/header.php'; ?>
<div class="scroll-main bgededed">
    <div class="video-info">
        <div class="video-box">
            <video src="<?php echo static_file('m/video/mp4.mp4'); ?>" controls="controls" poster="<?php echo static_file('m/img/pic1.jpg'); ?>"></video>
        </div>
        <div class="info">
            <div class="tit">
                <p>这游戏叫谁西安急倒是法搜发动机偶是福克斯粉红色放</p>
            </div>
            <div class="time">
                <p>更新时间2016-02-16 <span class="line"></span> 200次浏览</p>
            </div>
            <div class="con">
                <div class="show">
                    <p>就死了粉红色肯定会发你肦水电费会发你肦水电费会发你肦水电费会发你肦水电费会发你水电...</p>
                </div>
                <div class="hide">
                    <p>就死了粉红色肯定会发你肦水电费会发你肦水电费会发你肦水电费会发你肦水电费会发你肦水电费水电费阿斯蒂芬双方都；</p>
                    <p>近似偶奥火服都说多扶你水电费沙发费沙发费沙发费沙发费沙发费沙发费沙发大水电费水电费水电费；</p>
                </div>
                <div class="btn"></div>
            </div>
            <div class="page">
                <a href="" title="" class="box">
                    <p><i></i>上一篇：标题</p><i class="icon"></i>
                </a>
                <a href="" title="" class="box">
                    <p><i></i>下一篇：标题</p><i class="icon"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="recommend-video">
        <div class="title">
            <p>推荐视频</p>
        </div>
        <div class="list f-cb">
            <ul>
                <li>
                    <div class="save-btn cur"></div>
                    <a href="" title="" class="box">
                        <div class="img">
                            <img src="<?php echo static_file('m/img/pic1.jpg'); ?>" width="100%" height="100%" alt="">
                            <p class="time">01:41:44</p>
                        </div>
                        <div class="text">
                            <h3>仪表控制设备</h3>
                            <p>家里的事覅是点击覅世纪东方第三方</p>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="save-btn"></div>
                    <a href="" title="" class="box">
                        <div class="img">
                            <img src="<?php echo static_file('m/img/pic1.jpg'); ?>" width="100%" height="100%" alt="">
                            <p class="time">01:41:44</p>
                        </div>
                        <div class="text">
                            <h3>仪表控制设备</h3>
                            <p>家里的事覅是点击覅世纪东方第三方</p>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="save-btn"></div>
                    <a href="" title="" class="box">
                        <div class="img">
                            <img src="<?php echo static_file('m/img/pic1.jpg'); ?>" width="100%" height="100%" alt="">
                            <p class="time">01:41:44</p>
                        </div>
                        <div class="text">
                            <h3>仪表控制设备</h3>
                            <p>家里的事覅是点击覅世纪东方第三方</p>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="save-btn"></div>
                    <a href="" title="" class="box">
                        <div class="img">
                            <img src="<?php echo static_file('m/img/pic1.jpg'); ?>" width="100%" height="100%" alt="">
                            <p class="time">01:41:44</p>
                        </div>
                        <div class="text">
                            <h3>仪表控制设备</h3>
                            <p>家里的事覅是点击覅世纪东方第三方</p>
                        </div>
                    </a>
                </li>
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
	
    $('.video-info .con .btn').click(function(){
        if($(this).hasClass('cur')){
            $(this).removeClass('cur');
            $('.video-info .con .show').show();
            $('.video-info .con .hide').hide();
        }else{
            $(this).addClass('cur');
            $('.video-info .con .show').hide();
            $('.video-info .con .hide').show();
        }
    })

})
</script>
</body>
</html>