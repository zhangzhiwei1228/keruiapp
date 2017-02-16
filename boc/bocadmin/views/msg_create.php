
<div class="btn-group">
    <a href="<?php echo site_urlc('msg/index')?>" class='btn'> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?></a>
</div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
    <h3> <i class="fa fa-pencil"></i> <span class="badge badge-success pull-right"><?php echo $title; ?></span> <?php echo lang('add') ?></h3>
    <?php echo form_open(current_urlc(),array("class"=>"form-horizontal","id"=>"frm-create")); ?>

    <div class="boxed-inner seamless">
        <div class="control-group">
            <label class="control-label" for="title"> 发送区域 </label>
            <div class="controls">
                <select name="area">
                    <option value="0">全部</option>
                    <?php if($area) {?>
                        <?php foreach($area as $row) {?>
                            <option value="<?php echo $row['id']?>"><?php echo $row['title']?></option>
                        <?php }?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group uefull">
            <textarea id="content" name="content" ></textarea>
        </div>

    </div>

    <div class="boxed-footer">
        <?php if ($this->ccid): ?>
            <input type="hidden" name="ccid" value="<?php echo $this->ccid ?>">
        <?php endif ?>
        <input type="hidden" name="cid" value="<?php echo $this->cid ?>">
        <input type="submit" value=" <?php echo lang('submit'); ?> " class='btn btn-primary'>
        <input type="reset" value=' <?php echo lang('reset'); ?> ' class="btn btn-danger">
    </div>
</form>
</div>

<?php include_once 'inc_ui_media.php'; ?>
<script type="text/javascript">
    require(['jquery','adminer/js/ui','adminer/js/media'],function($,ui,media){
      ui.editor_create('content');
  });
</script>
