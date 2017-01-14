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
<title>南京交道</title>
<script>
  var STATIC_URL = "<?php echo STATIC_URL ?>";
  var GLOBAL_URL = "<?php echo GLOBAL_URL ?>";
  var UPLOAD_URL = "<?php echo UPLOAD_URL ?>";
  var SITE_URL   = "<?php echo site_url() ?>";
</script>

<?php
  echo static_file('jQuery.js');
  echo static_file('m/css/reset.css');
  echo static_file('m/css/style.css');
?>
</head>

<body>
    <div class="bg-img" style="background:url(<?php echo static_file('m/img/jd_bg.png'); ?> ) no-repeat center center;background-size:100% 100%;">
    	<div class="ewm-box">
        <img src="<?php echo static_file('m/img/liantu.png'); ?> " alt=""> 
      </div>
      <!-- <div class="shade">
        <span class="close"></span>
        <div class="p">
          <p><span>1. </span>点击右上角<span>“菜单”</span>按钮</p>
          <p><span>2. </span>选择在<span>“浏览器”</span>中打开</p>
        </div>
      </div> -->
    </div>
<?php
	echo static_file('m/js/adaptive-version2.js');
?>
<script>
window['adaptive'].desinWidth = 750;
window['adaptive'].init();

var h=$(window).height();
$(".bg-img").height(h);
$(".shade").height(h);

$(".close").click(function(){
  $(this).parent(".shade").hide();
})
</script>
</body>
</html>