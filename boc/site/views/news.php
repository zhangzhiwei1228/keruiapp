<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php //include_once VIEWS.'inc/header.php'; ?>
    <div class="news-main">
		<?php foreach($notices as $row) {?>
			<?php if($row['type'] == 1) {?>
				<div class="top-box">
					<div class="top f-cb">
						<div class="name"><?php echo $row['title']?></div>
						<div class="time"><?php echo date('Y-m-d H:i:s',$row['timeline'])?></div>
					</div>
					<div class="bot">
						<p><?php echo $row['content']?></p>
					</div>
				</div>
			<?php } elseif($row['type'] == 2) {?>
			<?php } else {?>
				<div class="top-box">
					<div class="top f-cb">
						<div class="name">管理员通知</div>
						<div class="time"><?php echo date('Y-m-d H:i:s',$row['ctime'])?></div>
					</div>
					<div class="bot">
						<p><?php echo $row['comment']?></p>
					</div>
				</div>
				<div class="bot-box">
					<div class="tit"><p>我的留言</p></div>
					<div class="con">
						<p><?php echo $row['content']?></p>
					</div>
					<div class="bot">
						<p>来自：<?php echo $row['nickname']?>  时间：<?php echo date('Y-m-d H:i:s',$row['timeline'])?></p>
					</div>
				</div>
			<?php }?>
		<?php }?>
    </div>
	<div class="page">
		<?php echo $pages?>
	</div>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('m/js/main.js');
?>
<script>
$(function(){
	
})
</script>
</body>
</html>