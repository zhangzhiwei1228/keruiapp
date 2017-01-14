<div class="btn-group">
	<a href="<?php echo site_urlg('demand/index')?>" class='btn btn-primary'> <i class="fa fa-arrow-left"></i> 返回需求 </a>
</div>

<div class="clearfix"><p></p></div>
<?php include_once 'inc_ui_limit.php'; ?>
<form action="<?php echo site_url($this->class . '/index/'); ?>" method="GET" class="form-horizontal">
  <span>内容:</span>
  <input type="text" class="" name="content" value="<?php echo $this->input->get('content') ?>">
  <input class="btn btn-primary" type="submit" id="btn_search" value="搜索">
</form>
<div class="clearfix"><p></p></div>
<div class="clearfix"><p></p></div>

<div class="boxed">
	<div class="boxed-inner seamless">

<table class="table table-striped table-hover select-list">
	<thead>
		<tr>
			<th class="width-small"><input id='selectbox-all' type="checkbox" > </th>
			<th>评论用户</th>
			<th>内容</th>
			<th>回复数</th>
			<th>评论时间</th>
			<th class="span1">操作</th>
		</tr>
	</thead>
	<tbody class="sort-list">
		<?php foreach ($list as $v):?>
		<tr data-id="<?php echo $v['id'] ?>" data-sort="<?php echo $v['sort_id'] ?>">
			<td><input class="select-it" type="checkbox" value="<?php echo $v['id']; ?>" ></td>
		  <td> <?php echo (isset($v['aid_info']) && $v['aid_info'])?$v['aid_info']['nickname']:'无' ?></td>
		  <td> <?php echo $v['content'] ?></td>
		  <td> <a href="<?php echo site_urlg('demand_reply/index', array(), array('dcid'=>$v['id'])); ?>"><?php echo $v['reply_count']; ?></a></td>
			<td> <?php echo date("Y/m/d H:i:s",$v['timeline']); ?> </td>
			<td>
				<div class="btn-group">
					<?php include 'inc_ui_audit.php'; ?>
					<a class='btn btn-small' href=" <?php echo site_urlg( $this->router->class.'/edit/'.$v['id']) ?> " title="<?php echo lang('edit') ?>"> <i class="fa fa-pencil"></i> <?php // echo lang('edit') ?></a>
					<a class='btn btn-danger btn-small btn-del' data-id="<?php echo $v['id'] ?>" href="#"  title="<?php echo lang('del') ?>"> <i class="fa fa-times"></i> <?php // echo lang('del') ?></a>
				</div>
			</td>
		</tr>
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
require(['adminer/js/ui'],function(ui){
	var demand = {
		url_del: "<?php echo site_urlg('demand/delete'); ?>"
		,url_audit: "<?php echo site_urlg('demand/audit'); ?>"
		,url_flag: "<?php echo site_urlg('demand/flag'); ?>"
		,url_sortid: "<?php echo site_urlg('demand/sortid'); ?>"
		,url_sort_change: "<?php echo site_urlg('demand/sort_change'); ?>"
		,url_copy: "<?php echo site_urlg('demand/copypro'); ?>"
	};
	ui.fancybox_img();
	ui.btn_delete(demand.url_del);		// 删除
	ui.btn_audit(demand.url_audit);	// 审核
	ui.btn_flag(demand.url_flag);		// 推荐
	// ui.sortable(demand.url_sortid);	// 排序  拖动排序和序号排序在firefox中有bug
	ui.sort_change(demand.url_sort_change); // input 排序
	ui.btn_copy(demand.url_copy);    // 热门审核
});
</script>
