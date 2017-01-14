

<div class="boxed">
	<div class="boxed-inner">
	<h3> <?php echo $title ?> </h3>
	<p style='display:none;'><input type="checkbox" id="selectbox-all"></p>
	<ul class="boxed-list select-list sort-list">
	<?php foreach ($list as $v):?>
		<li data-id="<?php echo $v['id'] ?>" data-sort="<?php echo $v['sort_id'] ?>">
		 <span> <input class="select-it" type="checkbox" value="<?php echo $v['id']; ?>" > </span>
		 <span><?php if($v['id']==1){ ?>IOS<?php  }else{echo "安卓端";} ?>   版本号：<?php echo $v['version'] ?> </span>
		 <span> 版本信息：<?php echo $v['versioninfo'] ?> </span>
		 <span> 等级：<?php echo $v['level'] ?> </span>
		 <div class="btn-group pull-right">
		 	<a class="btn btn-small btn-edit" href="#" title="<?php echo lang('edit') ?>" data-id="<?php echo $v['id']; ?>"> <i class="fa fa-pencil"></i></a>
		 </div>
		 </li>
	<?php endforeach; ?>
	</ul>
	</div>
</div>


<?php echo $pages; ?>

<div id="lists-modal" class="modal hide fade">
<?php echo form_open('#',array("class"=>"form-horizontal","id"=>"frm-list")); ?>

	<?php echo form_hidden('cid',$_GET['c']) ?>
	<?php if ($this->ccid): ?>
	<input type="hidden" name="ccid" value="<?php echo $this->ccid ?>">
	<?php endif ?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		<h3> <i class="fa fa-list"></i> 清单 </h3>
	</div>

	<div class="modal-body seamless">

		<div class="control-group">
			<label class="control-label" for="version">版本号：</label>
			<div class="controls">
				<input type="text" id="version" name="version" value="" >
				<span class="help-inline"></span>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="versioninfo">版本信息：</label>
			<div class="controls">
				<input type="text" id="versioninfo" name="versioninfo" value="">
				<span class="help-inline"></span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="level">等级：</label>
			<div class="controls">
				<input type="text" id="level" name="level" value="">
				<span class="help-inline"></span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="url">更新地址：</label>
			<div class="controls">
				<input type="text" id="url" name="url" value="">
				<span class="help-inline"></span>
			</div>
		</div>

	</div>

	<div class="modal-footer">
		<input type="submit" value=" <?php echo lang('submit'); ?> " class='btn btn-primary'>
		<input type="reset" value=' <?php echo lang('reset'); ?> ' class="btn btn-danger">
		<a href="#"  data-dismiss="modal" aria-hidden="true" class="btn btn-danger"> <?php echo lang('close') ?></a>
	</div>
</form>
</div>

<script>

require(['adminer/js/ui','adminer/js/platform'],function(ui,platform){

	var form_list = {
		cid : "<?php echo $this->cid ?>"
		,id:0
		,action : "create"
		,url_create : "<?php echo site_urlc('platform/create'); ?>"
		,url_edit : "<?php echo site_urlc('platform/edit'); ?>"
		,url_del: "<?php echo site_urlc('platform/delete'); ?>"
		,url_audit: "<?php echo site_urlc('platform/audit'); ?>"
		// TODO: update view template aflter create/edit done
		,url_sort: "<?php echo site_urlc('platform/sortid'); ?>"
	}
	platform(form_list);

	ui.fancybox_img();
	ui.btn_delete(form_list.url_del);		// 删除
	ui.btn_audit(form_list.url_audit);	// 审核
	ui.sortable(form_list.url_sort);
});
</script>
