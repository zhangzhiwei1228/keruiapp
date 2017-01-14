
<div class="btn-group"><a href="<?php echo site_url('demand/index');?>" class="btn"> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?> </a></div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
	<h3> <i class="fa fa-pencil"></i> 编辑消息 <span class="badge badge-success pull-right"><?php echo $title; ?></span></h3>
	<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'frm-edit')); ?>

	<div class="boxed-inner seamless">

		<div class="control-group">
			<label for="title" class="control-label">	发布用户:</label>
			<div class="controls">
				<span><?php echo (isset($it['aid_info']) && $it['aid_info'])?$it['aid_info']['nickname']:'无' ?></span>
				<span class="help-inline"></span>
			</div>
		</div>

		<div class="control-group">
			<label for="title" class="control-label">标题:</label>
			<div class="controls">
				<input type="text" name="title" id="title" value="<?php echo set_value('title',$it['title']); ?>"  placeholder="栏目名称" required=1>
				<span class="help-inline"></span>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="status"> 行业:</label>
			<div class="controls">
				<?php
				echo ui_btn_select('ctype',set_value("ctype",$it['ctype']),$ctypes);
				?>
				<span class="help-inline"></span>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="status"> 地区:</label>
			<div class="controls">
				<span><?php echo $it['addr_str'] ?></span>
				<span class="help-inline"></span>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="status"> 标签:</label>
			<div class="controls">
				<input type="text" name="tags" id="tags" value="<?php echo set_value('tags',$it['tags']); ?>"  placeholder="标签">
				<span class="help-inline"></span>
			</div>
		</div>

		<div class="control-group">
			<label for="title" class="control-label">发布时间:</label>
			<div class="controls">
				<span><?php echo date('Y-m-d H:i:s', $it['timeline']); ?></span>
			</div>
		</div>

		<div class="control-group uefull">
			<label for="content" class="control-label">内容:</label>
			<div class="controls">
				<textarea id="content" name="content" style="width: 400px; height: 200px;"> <?php echo set_value('content',$it['content']); ?></textarea>
				<span class="help-inline"></span>
			</div>
		</div>

		<!-- 图片上传 -->
		<div class="control-group">
			<label for="img" class="control-label">图片：</label>
			<div class="controls">
				<div class="btn-group">
					<span class="btn btn-success">
						<i class="fa fa-upload"></i>
						<span> <?php echo lang('upload_file') ?> </span>
						<input class="fileupload" type="file" accept="">
					</span>
					<input type="hidden" name="photo" class="form-upload" data-more="0" value="<?php echo set_value('photo',$it['photo']) ?>">
					<input type="hidden" name="thumb" class="form-upload-thumb" value="<?php echo set_value('thumb',$it['thumb']) ?>">
				</div>
			</div>
		</div>
		<div id="js-photo-show" class="js-img-list-f">
		</div>
		<div class="clear"></div>
		<!-- 图片上传 -->

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

		media.init();
		var demands_photos = <?php echo json_encode(one_upload($it['photo'])) ?>;
		media.show(demands_photos,"photo");

	});
</script>
