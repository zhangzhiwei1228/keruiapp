
<div class="btn-group"><a href="<?php echo site_urlc('comment/index');?>" class="btn"> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?> </a></div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
    <h3> <i class="fa fa-pencil"></i> <?php echo lang('edit') ?> <span class="badge badge-success pull-right"><?php echo $title; ?></span></h3>
    <?php echo form_open(current_urlc(), array('class' => 'form-horizontal', 'id' => 'frm-edit')); ?>

    <div class="boxed-inner seamless">
        <div class="control-group">
            <label for="title" class="control-label">项目的图片:</label>
            <div class="controls">
                <label><img src="<?php echo UPLOAD_URL.$it['thumb'] ?>" alt="<?php echo $it['title'];?>"></label>
            </div>
        </div>
        <div class="control-group">
            <label for="title" class="control-label">评论人:</label>
            <div class="controls">
                <label><?php echo $it['nickname']?></label>
            </div>
        </div>
        <div class="control-group">
            <label for="title" class="control-label">评论的标题:</label>
            <div class="controls">
                <label><?php echo $it['title']?></label>
            </div>
        </div>
        <div class="control-group">
            <label for="title" class="control-label">评论的内容:</label>
            <div class="controls">
                <label><?php echo $it['content']?></label>
            </div>
        </div>
        <div class="control-group uefull">
            <textarea id="editor_id" name="comment"> <?php echo set_value('comment',$it['comment']); ?></textarea>
        </div>


    </div>
    <div class="boxed-footer">
        <?php if ($this->ccid): ?>
            <input type="hidden" name="ccid" value="<?php echo $this->ccid ?>">
        <?php endif ?>
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
    });
</script>
