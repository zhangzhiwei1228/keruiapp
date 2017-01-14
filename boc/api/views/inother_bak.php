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
       <div class="shade">
        <span class="close"></span>
        <div class="p">
          <p><span>1. </span>点击右上角<span>“菜单”</span>按钮</p>
          <p><span>2. </span>选择在<span>“浏览器”</span>中打开</p>
        </div>
        <div class="jt">
        	<img src="<?php echo static_file('m/img/jt.png'); ?> " alt="">
        </div>
        <a class="anr-or-ios por">下载安装</a>
      </div>
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


$(function(){
    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
        $('.anr-or-ios').click(function(event) {
            event.preventDefault();
            window.location.href="https://itunes.apple.com/cn/app/id1176836159";
        });
    } else if (/(Android)/i.test(navigator.userAgent)) {
        $('.anr-or-ios').click(function(event) {
            event.preventDefault();
            $(".ovfl").fadeIn();
            window.location.href ="http://m.shouji.360tpcdn.com/161222/ac9d22e6a9894faec43581856cb9f10d/com.bocweb.fly.jiaodao_2.apk";
        });
    }

    $('.anr-or-ios').click(function(event) {
        // event.preventDefault();
        $(".ovfl").fadeIn();
        // window.location.href ="<?php echo APK_URL.'release-v1.apk' ?>";
    });
    $(".wrap-ov").on('click', '.close', function(event) {
        event.preventDefault();
        $(".ovfl").fadeOut();
        /* Act on the event */
    });
});


</script>
</body>
</html>



