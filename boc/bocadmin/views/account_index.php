<?php $use_tree=get_lang('cfg','users_level'); ?>

<div class="btn-group">
	<a  class='btn btn-primary'> 会员管理 </a>
</div>
<p></p>

<?php include_once 'inc_ui_limit.php'; ?>
<form class="form-inline" action="<?php echo site_url($this->class.'/search'); ?>" method="GET">
<input type="text" name="phone" width="50px" value="" placeholder="手机"/>
<?php
$industries = list_coltypes(0,0,'industry');
if (isset($_GET['industry']) AND is_numeric($_GET['industry']) AND $_GET['industry']) {
	//echo ui_btn_select('industry',set_value("industry",$_GET['industry']),$industries);
}else{
	//echo ui_btn_select('industry',set_value("industry"),$industries);
}
?>
<input class="btn" type="submit" value="检索">
<a href="<?php echo site_url('coltypes/index').'?c=0&field=industry&rc='.$this->class; ?>" class="btn"><i class="fa fa-plus"></i> 行业管理</a>
</form>

<div class="clearfix"><p></p></div>
<div class="clearfix"><p></p></div>

<div class="boxed">
	<div class="boxed-inner seamless">

<table class="table table-striped table-hover select-list">
	<thead>
		<tr>
			<th class="width-small"><input id='selectbox-all' type="checkbox" > </th>
			<th>会员</th>
			<th>头像</th>
			<th>手机</th>
			<th>昵称</th>
			<th>省市</th>
			<th>行业</th>
			<th>注册时间</th>
			<th>操作</th>
			<th class="span1">删除</th>
		</tr>
	</thead>
	<tbody class="sort-list">
		<?php foreach ($list as $v):?>
		<tr data-id="<?php echo $v['id'] ?>" data-sort="<?php echo $v['sort_id'] ?>">
			<td><input class="select-it" type="checkbox" value="<?php echo $v['id']; ?>" ></td>
			<td>【<?php if($v['level']<4){ echo $use_tree[$v['level']];}?>】</td>
			<td>
                <?php if ($v['photo'] && $photo_info = one_upload($v['photo'])){?>
               <a class="fancybox-img" href="<?php echo UPLOAD_URL.$photo_info['url'] ?>" title="<?php echo $v['phone'] ?>">
					<img src="<?php echo UPLOAD_URL.$photo_info['url'] ?>" alt="<?php echo $v['phone'];?>">
				</a>
                <?php }else{ ?>
                 <a class="fancybox-img" href="<?php echo static_file('web/img/photo.jpg') ?>" title="<?php echo $v['phone'] ?>">
                    <img src="<?php echo static_file('web/img/photo.jpg') ?>" style="width:60px;height:50px" alt="<?php echo $v['phone'];?>">
                </a>
                <?php } ?>
             </td>
			<td><?php echo $v['phone']; ?>  </td>
			<td><?php echo $v['nickname']; ?></td>
			<td><?php echo $v['addr_str']?$v['addr_str']:'无' ?></td>
			<td><?php echo ui_btns_type($v['industry']); ?></td>
			<td> <?php echo date("Y/m/d H:i:s",$v['timeline']); ?> </td>
			<td>
				<div class="btn-group">
					<?php include 'inc_ui_audit.php'; ?>
					<a class='btn btn-small' href=" <?php echo site_url( $this->router->class.'/edit/'.$v['id']) ?> " title="<?php echo lang('edit') ?>"> <i class="fa fa-pencil"></i> </a>
				</div>
			</td>
			<td>
				<div class="btn-group">
					<a class='btn btn-danger btn-small btn-del' data-id="<?php echo $v['id'] ?>" href="#"  title="<?php echo lang('del') ?>"> <i class="fa fa-times"></i> </a>
				</div>
			</td>
		<?php endforeach;?>
	</tbody>
</table>

	</div>
</div>

<div class="btn-group">
	<a id='select-all' class='btn' href="#"> <i class=""></i> <?php echo lang('select_all') ?> </a>
	<a id='unselect-all' class='btn hide' href="#"> <i class=""></i> <?php echo lang('unselect') ?> </a>
	<a id="btn-del" class='btn btn-danger' href="#"> <i class="fa fa-times"></i> <?php echo lang('del') ?> </a>
	<a id="btn-audit" class='btn' href="#" data-audit='1'><?php echo lang('audit') ?></a>
	<a id="btn-audit" class='btn' href="#"  data-audit='0'>取消审核</a>
</div>

<?php echo $pages; ?>

<script>
require(['adminer/js/ui', 'tools'],function(ui, tools){
  tools.selectLink.init({
    eles:['province_id','city_id'],
    type:"addr",
    defaultValues:[<?php echo (isset($_GET['province_id']) && is_numeric($_GET['province_id']))?$_GET['province_id']:'' ?>, <?php echo (isset($_GET['city_id']) && is_numeric($_GET['city_id']))?$_GET['city_id']:'' ?>, 1368],
    url: '<?php echo site_url("/district/index") ?>'
  });

	var account = {
		url_del: "<?php echo site_urlg('account/delete'); ?>"
		,url_audit: "<?php echo site_urlg('account/audit'); ?>"
		,url_flag: "<?php echo site_urlg('account/flag'); ?>"
		,url_sortid: "<?php echo site_urlg('account/sortid'); ?>"
		,url_sort_change: "<?php echo site_urlg('account/sort_change'); ?>"
		,url_copy: "<?php echo site_urlg('account/copypro'); ?>"
	};
	ui.fancybox_img();
	ui.btn_delete(account.url_del);		// 删除
	ui.btn_audit(account.url_audit);	// 审核
	ui.btn_flag(account.url_flag);		// 推荐
	// ui.sortable(account.url_sortid);	// 排序  拖动排序和序号排序在firefox中有bug
	ui.sort_change(account.url_sort_change); // input 排序
	ui.btn_copy(account.url_copy);    // 热门审核
});
</script>
