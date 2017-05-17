<?php $use_tree=get_lang('cfg','users_level'); ?>

<div class="btn-group">

<a href="<?php echo site_urlc('account'); ?>" class="btn"> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list') ?> </a>

</div>
<?php include_once 'inc_form_errors.php';?>

<div class="boxed">
	<h3> <i class="fa fa-pencil"></i> 查看用户 <span class="badge badge-success pull-right"><?php echo $title; ?></span> </h3>
	<?php echo form_open(current_urlc(), array('class' => 'form-horizontal', 'id' => 'frm-edit')); ?>
	<div class="row-fluid">
		<div class="boxed-inner seamless " >
			<div class="control-group">
				<label for="title" class="control-label">帐号:</label>
				<div class="controls">
					<input type="text" name="phone" id="phone" value="<?php echo set_value('phone',$it['phone']); ?>"  placeholder="栏目名称" required=1 maxlength="20">
				</div>
			</div>

			<div class="control-group">
				<label for="id" class="control-label">会员ID：</label>
				<div class="controls">
					<?php echo $it['id']; ?>
				</div>
			</div>
			<div class="control-group">
				<label for="id" class="control-label">注册时间</label>
				<div class="controls">
					<?php echo date("Y-m-d H:i:s",$it['timeline']); ?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"> 平台 </label>
				<div class="controls">
					<select name="terminalNo">
						<option value="1" <?php echo $it['terminalNo'] == 1 ? 'selected' : ''?> >IOS</option>
						<option value="2" <?php echo $it['terminalNo'] == 2 ? 'selected' : ''?>>Android</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"> 所属地区 </label>
				<div class="controls">
					<select name="area">

						<?php if(count($areas) > 0) {?>
							<?php foreach($areas as $row) {?>
								<option value="<?php echo $row['id']?>" <?php echo $row['id'] == $it['area'] ? 'selected' : '' ?>><?php echo $row['title']?></option>
							<?php }?>
						<?php } else {?>
							<option value="0">请先设置</option>
						<?php }?>

					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"> 一级产品权限 </label>
				<div class="controls">
					<?php if(count($pfrist) > 0) {?>
						<?php foreach($pfrist as $row) {?>
							<?php $pfrist_ = strstr($it['pfrist'],$row['id']);?>
							<input class="select-it" type="checkbox" value="<?php echo $row['id']; ?>" <?php if($pfrist_) echo 'checked';?>  name="pfrist[]"><?php echo $row['title']?>
						<?php }?>
					<?php } else {?>
						请先设置
					<?php }?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"> 二级产品权限 </label>
				<div class="controls">
					<?php if(count($psecond) > 0) {?>
						<?php foreach($psecond as $row) {?>
							<?php $psecond_ = strstr($it['psecond'],$row['id']);?>
							<input class="select-it" type="checkbox" value="<?php echo $row['id']; ?>" <?php if($psecond_) echo 'checked';?> name="psecond[]"><?php echo $row['title']?>
						<?php }?>
					<?php } else {?>
						请先设置
					<?php }?>
				</div>
			</div>
			<!--<div class="control-group">
				<label for="phone" class="control-label">手机号码：</label>
				<div class="controls">
					<?php /*echo $it['phone']; */?>
				</div>
			</div>-->
			<div class="control-group">
				<label for="title" class="control-label">昵称</label>
				<div class="controls">
					<?php echo $it['nickname']; ?>
				</div>
			</div>
			<!-- 图片上传 -->
			<div class="control-group">
				<label for="img" class="control-label">头像：</label>
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
		</div>

		<div class="boxed-footer">
			<input type="hidden" name="id" value="<?php echo $it['id'] ?>">
			<input type="hidden" name="cid" value="<?php echo $this->cid ?>">
			<input type="submit" value="<?php echo lang('submit') ?>" class="btn btn-primary">
			<input type="reset" value="<?php echo lang('reset') ?>" class="btn btn-danger">
		</div>
	</form>
</div>
<?php include_once 'inc_ui_media.php'; ?>
<script type="text/javascript">
	require(['adminer/js/media'],function(media){
		media.init();
		var articles_photos = <?php echo json_encode(one_upload($it['photo'])) ?>;
		media.show(articles_photos,"photo");

	});
</script>
