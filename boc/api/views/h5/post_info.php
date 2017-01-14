<?php
$tid = isset($reg[0]) ? $reg[0] : show_404();
$CI->load->model('discuss_model','mdiscuss');
$it = $CI->mdiscuss->get_one(array('id' => $tid, 'audit' => 1),'*', 'vdiscuss');
?>
<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
    <div class="w-post">
        <img src="<?php echo !empty($it['account_photo']) ? UPLOAD_URL.tag_photo($it['account_photo']) : static_file('api/img/pic_4.jpg'); ?> " alt="">
        <div class="post-title fl">

            <span><?php echo $it['account_nickname']; ?></span>
            <em><?php echo date('Y-m-d H:i:s', $it['timeline']); ?></em>
        </div>    
    </div> 
    <div class="post-mid f-cb w92">
       <h3><?php echo $it['title']; ?></h3>
       <p><?php echo $it['content']?></p>
       <img src="<?php echo !empty($it['photo']) ? UPLOAD_URL.tag_photo($it['photo']) : static_file('api/img/pic_3.jpg'); ?> " alt="">
    </div>
    <div class="post-bot">
        <img src="<?php echo static_file('api/img/pic_5.png'); ?> ">
    </div>
    <div class="line-box"></div>
<?php
	echo static_file('api/js/main.js');
?>
<script>
$(function(){
	
})
</script>
</body>
</html>