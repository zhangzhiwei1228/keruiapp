<?php $use_tree=get_lang('cfg','users_level'); ?>

<div class="btn-group">

<a href="<?php echo site_url('account'); ?>" class="btn"> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list') ?> </a>

</div>
<?php include_once 'inc_form_errors.php';?>

<div class="boxed">
	<h3> <i class="fa fa-pencil"></i> 查看用户 <span class="badge badge-success pull-right"><?php echo $title; ?></span> </h3>
	<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'frm-edit')); ?>
	<div class="row-fluid">
		<div class="boxed-inner seamless " >
			<div class="control-group">
				<label for="title" class="control-label">手机:</label>
				<div class="controls">
					<input type="text" name="phone" id="phone" value="<?php echo set_value('phone',$it['phone']); ?>"  placeholder="栏目名称" required=1>
				</div>
			</div>

			<div class="control-group">
				<label for="id" class="control-label">会员ID：</label>
				<div class="controls">
					<?php echo $it['id']; ?> &nbsp; &nbsp;&nbsp;&nbsp;
					注册时间：<?php echo date("Y-m-d H:i:s",$it['timeline']); ?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="status">会员类别：</label>
				<div class="controls">
					【<?php echo $use_tree[$it['level']];?>】
					<?php if(!empty($it['buytimeline'])) {?>会员时间 <?php echo $it['buytimeline']; ?><?php } ?>
					<span class="help-inline"></span>
				</div>
			</div>
			<div class="control-group">
				<label for="phone" class="control-label">手机号码：</label>
				<div class="controls">
					<?php echo $it['phone']; ?>
				</div>
			</div>
			<div class="control-group">
				<label for="title" class="control-label">昵称</label>
				<div class="controls">
					<?php echo $it['nickname']; ?>
				</div>
			</div>
			<div class="control-group">
				<label for="title" class="control-label">个性签名</label>
				<div class="controls">
					<?php echo $it['autograph']; ?>
				</div>
			</div>
			<div class="control-group">
				<label for="title" class="control-label">省市</label>
				<div class="controls">
					<?php echo $it['addr_str']?$it['addr_str']:'无' ?>
				</div>
			</div>
			<div class="control-group">
				<label for="title" class="control-label">行业</label>
				<div class="controls">
					<?php echo ui_btns_type($it['industry']); ?>
				</div>
			</div>
			<div class="control-group">
				<label for="title" class="control-label">是否关联第三方</label>
				<div class="controls">
					<?php echo $it['sid'] ?>
				</div>
			</div>
			<div class="control-group">
				<label for="title" class="control-label">综合评分</label>
				<div class="controls">
					<?php echo $it['score'] ?>
				</div>
			</div>
			<?php if(!empty($orderdata)){ ?>
			<div class="control-group">
				<label for="title" class="control-label">购买会员记录</label>
				<div class="controls">
					<?php foreach ($orderdata as $key => $v): ?>
						<?php if($v['pay']==1){echo "【已付】";} ?>订单号:<?php echo $v['title']; ?>; ￥<?php echo $v['price']; ?> 时间:<?php echo date("Y-m-d H:i:s",$v['timeline']);?>;<br/>
					<?php endforeach ?>
				</div>
			</div>
			<?php } ?>



	</div>

		<div class="boxed-footer">
			<input type="hidden" name="id" value="<?php echo $it['id'] ?>">
			<input type="submit" value="<?php echo lang('submit') ?>" class="btn btn-primary">
			<input type="reset" value="<?php echo lang('reset') ?>" class="btn btn-danger">
		</div>
	</form>
</div>
