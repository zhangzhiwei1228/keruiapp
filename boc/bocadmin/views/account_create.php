
<div class="btn-group">
    <a href="<?php echo site_urlc('account/index')?>" class='btn'> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?></a>
</div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
    <h3> <i class="fa fa-plus"></i> <span class="badge badge-success pull-right"><?php echo $title; ?></span> <?php echo lang('add') ?></h3>
    <?php echo form_open(current_urlc(),array("class"=>"form-horizontal","id"=>"frm-create")); ?>

        <div class="boxed-inner seamless">
            <div class="control-group">
                <label class="control-label" for="title"> 帐号 </label>
                <div class="controls">
                    <input type="text" id="phone" name="phone" value="<?php echo set_value("phone") ?>" maxlength="20" required=1 x-webkit-speech>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="title"> 密码 </label>
                <div class="controls">
                    <input type="password" id="pwd" name="pwd" value="<?php echo set_value("pwd") ?>" maxlength="12" required=1 x-webkit-speech>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="title"> 平台 </label>
                <div class="controls">
                    <select name="terminalNo">
                        <option value="1">IOS</option>
                        <option value="2">Android</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="title"> 所属地区 </label>
                <div class="controls">
                    <select name="area">

                        <?php if(count($areas) > 0) {?>
                            <?php foreach($areas as $row) {?>
                                <option value="<?php echo $row['id']?>"><?php echo $row['title']?></option>
                            <?php }?>
                        <?php } else {?>
                            <option value="0">请先设置</option>
                        <?php }?>

                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="title" class="control-label">时间:</label>
                <div class="controls">
                    <div class="input-append date timepicker">
                        <input type="text" value="<?php echo date("Y-m-d H:i:s",set_value('timeline',now())); ?>" id="timeline" name="timeline" data-date-format="yyyy-mm-dd hh:ii:ss">
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                </div>
            </div>
            <!-- 图片上传 -->
            <div class="control-group">
                <label for="img" class="control-label"><?php echo lang('photo') ?>：</label>
                <div class="controls">
                    <div class="btn-group">
                        <span class="btn btn-success">
                            <i class="fa fa-upload"></i>
                            <span> <?php echo lang('upload_file') ?> </span>
                               [100*100]
                            <input class="fileupload" type="file" accept="">
                        </span>
                        <input type="hidden" name="photo" class="form-upload" data-more="0" value="<?php echo set_value("photo") ?>">
                        <input type="hidden" name="thumb" class="form-upload-thumb" value="<?php echo set_value("thumb") ?>">
                    </div>
                </div>
            </div>

            <!-- 对应 photo 模板容器 js 开头为js操作的容器 -->
            <div id="js-photo-show" class="js-img-list-f">
                <!-- 模板 #tpl-img-list -->
            </div>
            <div class="clear"></div>

        </div>

        <div class="boxed-footer">
            <input type="hidden" name="cid" value="<?php echo $this->cid ?>">
            <?php if ($this->ccid): ?>
            <input type="hidden" name="ccid" value="<?php echo $this->ccid ?>">
            <?php endif ?>
            <input type="submit" value=" <?php echo lang('submit'); ?> " class='btn btn-primary'>
            <input type="reset" value=' <?php echo lang('reset'); ?> ' class="btn btn-danger">
        </div>
    </form>
</div>

<?php include_once 'inc_ui_media.php'; ?>

<script type="text/javascript">
require(['adminer/js/ui','adminer/js/media','bootstrap-datetimepicker.zh'],function(ui,media){
    // timepick
    $('.timepicker').datetimepicker({'language':'zh-CN','format':'yyyy/mm/dd hh:ii:ss','todayHighlight':true});
    // ueditor处理
    ui.editor_create('p_detail');
    ui.editor_create('p_desc');
    ui.editor_create('administration');

    // media 上传
    media.init();
    var accounts_photos = <?php echo json_encode(one_upload(set_value("photo"))) ?>;
    media.show(accounts_photos,"photo");
});

</script>
