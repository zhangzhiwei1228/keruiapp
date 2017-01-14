<?php 
 $CI->load->model('article_model','marticle');
if(isset($reg[0])){
  $id=$reg[0];
}else{
  $id=-1;
}

$rs=$CI->marticle->get_one($id);
if(!empty($rs)){
 $CI->marticle->add_click($rs['id']); 
}


 ?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="zh-CN" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="author" content="杭州博采网络科技股份有限公司-高端网站建设-http://www.bocweb.cn" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>详细</title>
<script>
  var STATIC_URL = "<?php echo STATIC_URL ?>";
  var GLOBAL_URL = "<?php echo GLOBAL_URL ?>";
  var UPLOAD_URL = "<?php echo UPLOAD_URL ?>";
  var SITE_URL   = "<?php echo site_url() ?>";
</script>
<?php
  echo static_file('m/css/reset.css');
  echo static_file('m/css/style.css');
?>
</head>
<?php 
echo static_file('m/swiper/swiper.css');
echo static_file('m/css/revolve.css');  
?>

<body>
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
    <?php }else{echo "暂无数据";} ?>
<?php
  echo static_file('m/js/adaptive-version2.js');
?>
<script>
window['adaptive'].desinWidth = 750;
window['adaptive'].init();  
</script>
</body>
</html>