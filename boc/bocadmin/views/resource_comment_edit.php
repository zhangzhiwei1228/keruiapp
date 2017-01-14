
<div class="btn-group"><a href="<?php echo site_urlg('resource_comment/index');?>" class="btn"> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?> </a></div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
	<h3> <i class="fa fa-pencil"></i> 编辑消息 <span class="badge badge-success pull-right"><?php echo $title; ?></span></h3>
	<?php echo form_open(current_urlg(array(), array('back_url'=>site_urlg($this->class.'/index'))), array('class' => 'form-horizontal', 'id' => 'frm-edit')); ?>

	<div class="boxed-inner seamless">

		<div class="control-group">
			<label for="title" class="control-label">	评论用户:</label>
			<div class="controls">
				<span><?php echo (isset($it['aid_info']) && $it['aid_info'])?$it['aid_info']['nickname']:'无' ?></span>
				<span class="help-inline"></span>
			</div>
		</div>

		<div class="control-group uefull">
			<label for="content" class="control-label">内容:</label>
			<div class="controls">
				<textarea id="content" name="content" style="width: 400px; height: 200px;"><?php echo set_value('content',$it['content']); ?></textarea>
				<span class="help-inline"></span>
			</div>
		</div>

		<div class="control-group">
			<label for="title" class="control-label">评论时间:</label>
			<div class="controls">
				<span><?php echo date('Y-m-d H:i:s', $it['timeline']); ?></span>
			</div>
		</div>

	</div>
	<div class="boxed-footer">
		<input type="hidden" name="id" value="<?php echo $it['id']?>">
		<input type="submit" value="<?php echo lang('submit') ?>" class="btn btn-primary">
		<input type="reset" value="<?php echo lang('reset') ?>" class="btn btn-danger">
	</div>
</form>
</div>

<!-- 注意加载顺序 -->
<?php include_once 'inc_ui_media.php'; ?>
<script type="text/javascript">
	require(['adminer/js/ui','adminer/js/media','bootstrap-datetimepicker.zh'],function(ui,media){
		$('.timepicker').datetimepicker({'language':'zh-CN','format':'yyyy/mm/dd hh:ii:ss','todayHighlight':true});
	});
</script>
