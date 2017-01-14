<?php 
$it = one_page(25);
?>
<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
    <div class="w-about-z">
        <span class="w94">
            <img src="<?php echo !empty($it['photo']) ? UPLOAD_URL.tag_photo($it['photo']) : static_file('api/img/pic_4.jpg'); ?> ">
        </span>
    </div> 
    <div class="about-z f-cb w92"> 
       <?php echo $it['content'] ?>
    </div>
<?php
	echo static_file('api/js/main.js');
?>
<script>
$(function(){
	
})
</script>
</body>
</html>