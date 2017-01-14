<?php 
$tid = isset($reg[0]) ? $reg[0] : show_404();
$CI->load->model('article_model','marticle');
$it = $CI->marticle->get_one(array('id'=>$tid,'cid'=>3));

?>
<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="w_about">
        <div class="w92">
            <p><h2><?php echo $it['title'] ?></h2></p>
            <p><span>发布日期：<?php echo date('Y-m-d',$it['timeline'])?></span></p>
            <!-- <img src="<?php echo static_file('api/img/a_2.png'); ?> "> 
             <p style="margin-bottom:30px;">12月2日，国际评级机构标准普尔在北京召开的媒体见面会上表示，随着中国经济增速放缓，未来两年内中国50大银行维持良好利润和适当资产质量的难度越来越大，对这些银行表现造成最大冲击的因素可能是中国房地产市场的急剧、持续下滑。</p>
             <p style="margin-bottom:30px;">标普对中国50大银行的界定是国有五大行、三大政策性银行、邮储银行、全国性股份制银行以及26家资产排名领先的城商行和农商行。</p>   --> 
             <?php echo $it['content'] ?>
        </div>    
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