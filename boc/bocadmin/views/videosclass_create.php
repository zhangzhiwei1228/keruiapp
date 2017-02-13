
<div class="btn-group">
    <a href="<?php echo site_urlc('videosclass/index')?>" class='btn'> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?></a>
</div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
    <h3> <i class="fa fa-pencil"></i> <span class="badge badge-success pull-right"><?php echo $title; ?></span> <?php echo lang('add') ?></h3>
    <?php echo form_open(current_urlc(),array("class"=>"form-horizontal","id"=>"frm-create")); ?>

    <div class="boxed-inner seamless">
        <div class="boxed-inner seamless">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab6" data-toggle="tab">ZH标题</a></li>
                    <li class=""><a href="#tab7" data-toggle="tab">FR标题</a></li>
                    <li class=""><a href="#tab8" data-toggle="tab">ES标题</a></li>
                    <li class=""><a href="#tab9" data-toggle="tab">RU标题</a></li>
                    <li class=""><a href="#tab10" data-toggle="tab">EN标题</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab6">
                        <div class="control-group">
                            <div class="controls">

                                <input type="text" id="title" name="title" class='span7'>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="control-group">

                            <div class="controls">
                                <input type="text" id="FR_title" name="FR_title" class='span7'>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab8">
                        <div class="control-group">

                            <div class="controls">
                                <input type="text" id="ES_title" name="ES_title" class='span7'>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab9">
                        <div class="control-group">

                            <div class="controls">
                                <input type="text" id="RU_title" name="RU_title" class='span7'>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab10">
                        <div class="control-group">
                            <div class="controls">
                                <input type="text" id="EN_title" name="EN_title" class='span7'>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="clear"></div>
        <!-- 图片上传 -->

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

<script type="text/javascript">
    require(['jquery','adminer/js/ui','adminer/js/media'],function($,ui,media){
      ui.editor_create('content');
      media.sort('photo');
  });
</script>
