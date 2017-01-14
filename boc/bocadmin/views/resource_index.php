<?php if (false): ?>
<div class="btn-group">
	<a href="<?php echo site_urlg('resource/create')?>" class='btn btn-primary'> <i class="fa fa-plus"></i> <?php echo $title; ?> </a>
</div>
<?php endif; ?>

<?php include_once 'inc_ui_limit.php'; ?>
<form action="<?php echo site_url($this->class . '/index/'); ?>" method="GET" class="form-horizontal">
  <span>行业:</span>
  <?php
  array_unshift($ctypes, array(
    'id'=>'',
    'title'=>'全部',
  ));
  if (isset($_GET['ctype']) AND is_numeric($_GET['ctype']) AND $_GET['ctype']) {
  	echo ui_btn_select('ctype',set_value("ctype",$_GET['ctype']),$ctypes);
  } else {
  	echo ui_btn_select('ctype',set_value("ctype"),$ctypes);
  }
  ?>
  <span>所在位置:</span>
  <div style="display:inline">
    <select id="province_id"  name="province_id" class='province' class="bselect" data-size="auto" data-live-search="true"></select>
    <select id="city_id"  name="city_id" class='city' class="bselect" data-size="auto" data-live-search="true"></select>
  </div>
	<div class="clearfix"><p></p></div>
  <span>标题:</span>
  <input type="text" class="" name="title" value="<?php echo $this->input->get('title') ?>">
  <span>内容:</span>
  <input type="text" class="" name="content" value="<?php echo $this->input->get('content') ?>">
  <span>标签:</span>
  <input type="text" class="" name="tags" value="<?php echo $this->input->get('tags') ?>">
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
			<th>排序</th>
			<th>图</th>
			<th>发布用户</th>
			<th>标题</th>
			<th>行业</th>
			<th>地区</th>
			<th>标签</th>
			<th>浏览/推荐/评论</th>
			<th>发布时间</th>
			<th class="span1">操作</th>
		</tr>
	</thead>
	<tbody class="sort-list">
		<?php foreach ($list as $v):?>
		<tr data-id="<?php echo $v['id'] ?>" data-sort="<?php echo $v['sort_id'] ?>">
			<td><input class="select-it" type="checkbox" value="<?php echo $v['id']; ?>" ></td>
		  <td> <input type="text" class="sortid" value="<?php echo $v['sort_id']?>" data-id="<?php echo $v['id'] ?>"> </td>
			<td>
				<?php if ($v['photo'] && $photo_info = one_upload($v['photo'])): ?>
				<a class="fancybox-img" href="<?php echo UPLOAD_URL.$photo_info['url'] ?>" title="<?php echo $v['title'] ?>">
					<img src="<?php echo UPLOAD_URL.$photo_info['url'] ?>" alt="<?php echo $v['title'];?>">
				</a>
				<?php endif ?>
		  </td>
		  <td> <?php echo (isset($v['aid_info']) && $v['aid_info'])?$v['aid_info']['nickname']:'无' ?></td>
		  <td> <?php echo $v['title'] ?></td>
		  <td> <?php echo isset($ctypes[$v['ctype']])?$ctypes[$v['ctype']]['title']:'无'; ?> </td>
		  <td> <?php echo $v['addr_str']?$v['addr_str']:'无' ?> </td>
		  <td> <?php echo empty($v['tags'])?'无':$v['tags']; ?> </td>
		  <td> <?php echo $v['click'].' / '.$v['recommend_count'].' / ' ?> <a href="<?php echo site_urlg('resource_comment/index', array(), array('did'=>$v['id'])); ?>"><?php echo $v['comment_count']; ?></a></td>
			<td> <?php echo date("Y/m/d H:i:s",$v['timeline']); ?> </td>
			<td>
				<div class="btn-group">
					<?php include 'inc_ui_flag.php'; ?>
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
require(['adminer/js/ui', 'tools'],function(ui, tools){
  tools.selectLink.init({
    eles:['province_id','city_id'],
    type:"addr",
    defaultValues:[<?php echo (isset($_GET['province_id']) && is_numeric($_GET['province_id']))?$_GET['province_id']:'' ?>, <?php echo (isset($_GET['city_id']) && is_numeric($_GET['city_id']))?$_GET['city_id']:'' ?>, 1368],
    url: '<?php echo site_url("/district/index") ?>'
  });

	var resource = {
		url_del: "<?php echo site_urlg('resource/delete'); ?>"
		,url_audit: "<?php echo site_urlg('resource/audit'); ?>"
		,url_flag: "<?php echo site_urlg('resource/flag'); ?>"
		,url_sortid: "<?php echo site_urlg('resource/sortid'); ?>"
		,url_sort_change: "<?php echo site_urlg('resource/sort_change'); ?>"
		,url_copy: "<?php echo site_urlg('resource/copypro'); ?>"
	};
	ui.fancybox_img();
	ui.btn_delete(resource.url_del);		// 删除
	ui.btn_audit(resource.url_audit);	// 审核
	ui.btn_flag(resource.url_flag);		// 推荐
	// ui.sortable(resource.url_sortid);	// 排序  拖动排序和序号排序在firefox中有bug
	ui.sort_change(resource.url_sort_change); // input 排序
	ui.btn_copy(resource.url_copy);    // 热门审核
});
</script>
