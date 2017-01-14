<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="zh-CN" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="Keywords" content="<?php //echo $header['tags'];?>"/>
<meta name="Description" content="<?php //echo $header['intro'];?>"/>
<meta name="author" content="杭州博采网络科技股份有限公司-高端网站建设-http://www.bocweb.cn" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>
<?php
// if ($this->router->class == 'welcome' and $this->router->method == "index"){
//   echo $header['title'];
// }else{
// echo $CI->config->item('title_suffix');
// echo empty($header['title']) ? '':'-'.$header['title'];
// }
?>
</title>
<link href="<?php echo GLOBAL_URL ?>favicon.ico" rel="shortcut icon">
<script>
	var STATIC_URL = "<?php echo STATIC_URL ?>";
	var GLOBAL_URL = "<?php echo GLOBAL_URL ?>";
	var UPLOAD_URL = "<?php echo UPLOAD_URL ?>" ;
</script>
<?php
	echo static_file('reset.css');
	echo static_file('jQuery.js');
	echo static_file('jquery.easing.1.3.js');
	echo static_file('jquery.transit.js');
	echo static_file('prefixfree.min.js');
	echo static_file('html5.js');
	echo static_file('bocfe.js');
	echo static_file('plug.preload.js');
	echo static_file('api/js/jquery-2.1.1.min.js');
	//web
	echo static_file('api/css/style.css');
?>
<!--[if IE 6]>
	<?php
		echo static_file('IE6PNG.js');
	?>
	<script type="text/javascript">
		IE6PNG.fix('.png');
	</script>
<![endif]-->
