<!DOCTYPE html>
<html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
<?php //include_once VIEWS.'inc/header.php'; ?>
<div class="safe-main">
<?php echo $content?>
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