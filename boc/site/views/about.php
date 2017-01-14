<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<?php 
echo static_file('m/swiper/swiper.css');
echo static_file('m/css/revolve.css');  
?>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <?php if(!empty($rs)){ ?>
    <div class="limit-marker-show">
      <div class="title"><?php echo $rs['title']; ?></div>
      <div class="time">
        <ul>
          <li style="color:#666;">发布时间</li>
          <li style="color:#ededed">丨</li>
          <li style="color:#999;"><?php echo date("Y-m-d",$rs['timeline']) ?></li>
        </ul>
      </div>
      <div class="text">
        <?php echo $rs['content']; ?>
      </div>
    </div>
    <?php } ?>

    <?php include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('m/js/main.js');
	echo static_file('m/js/adaptive-version2.js');
?>
<script>
window['adaptive'].desinWidth = 750;
window['adaptive'].init();	
</script>
</body>
</html>