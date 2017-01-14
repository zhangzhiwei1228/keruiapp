<?php include_once 'inc_modules_path.php'; ?>

<h3>  <i class="fa fa-pencil"></i>  <?php echo $title; ?></h3>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
	<?php echo form_open(site_urlc($this->class.'/edit'), array('class' => 'form-horizontal', 'id' => 'frm-edit')); ?>

		<input class="hide" type="text" id="p-title" name="title" value="<?php echo set_value('title',$seo['title']) ?>">
		<input class="hide" type="text" id="p-title_seo" name="title_seo" value="<?php echo set_value('title_seo',$seo['title_seo']) ?>">
		<input class="hide" type="text" id="p-tags" name="tags" value="<?php echo set_value('tags',$seo['tags']) ?>">
		<textarea class="hide" id='p-intro' name="intro" rows='8' class='span4'><?php echo set_value('intro',$seo['intro']) ?></textarea>

		<div class="boxed-inner seamless">

			<?php if($this->cid==9) {?>
			<div class="control-group">
				<label for="title" class="control-label">邮箱:</label>
				<div class="controls">
					<input type="text" name="email" id="email" value="<?php echo set_value('email',$it['email']); ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="title" class="control-label">电话:</label>
				<div class="controls">
					<input type="text" name="tel" id="tel" value="<?php echo set_value('tel',$it['tel']); ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="title" class="control-label">QQ:</label>
				<div class="controls">
					<input type="text" name="qq" id="qq" value="<?php echo set_value('qq',$it['qq']); ?>">
				</div>
			</div>
			<?php }else{ ?>

			<div class="control-group uefull">
				<textarea id="editor_id" name="content" > <?php echo set_value('content',$it['content']); ?></textarea>
				<!-- <span class="help-inline"></span> -->
			</div> 
			<?php } ?>

			<!-- 图片上传 -->
			<!-- <div class="control-group">
				<label for="img" class="control-label">图片：</label>
				<div class="controls">
					<div class="btn-group">
						<span class="btn btn-success">
							<i class="fa fa-upload"></i>
							<span> <?php echo lang('upload_file') ?> </span>
							<input class="fileupload" type="file" accept="" data-for="photo" multiple="multiple">
						</span>
						<input type="hidden" name="photo" class="form-upload" data-more="1" value="<?php echo $it['photo'] ?>">
						<input type="hidden" name="thumb" class="form-upload-thumb" value="<?php echo $it['thumb'] ?>">
					</div>
				</div>
			</div>
			
			<div id="js-photo-show" class="js-img-list-f">
				模板 #tpl-img-list
			</div>
			 -->
			<div class="clear"></div>

		</div>

		<div class="boxed-footer">
			<input type="hidden" name="cid" value="<?php echo $this->cid ?>">
			<input type="hidden" name="id" value="<?php echo $it['id']?>">
			<input type="submit" value="<?php echo lang('submit') ?>" class="btn btn-primary">
			<input type="reset" value="<?php echo lang('reset') ?>" class="btn btn-danger">
		</div>
	</form>
</div>

<?php include_once 'inc_ui_media.php'; ?>

<script type="text/javascript">
require(['jquery','adminer/js/ui','adminer/js/media'],function($,ui,media){
	ui.editor_create('editor_id');
	var page_photos = <?php echo json_encode(list_upload($it['photo'])) ?>;
	media.init();
	media.show(page_photos,'photo');
	media.sort('photo');
	$("#js-photo-show" ).sortable().disableSelection();
});
</script>
<?php //echo static_file('adminer/js/page.js'); ?>
