<?php
    switch($this->cid) {
        case 22:
            $level = 1;
            break;
        case 23:
            $level = 2;
            break;
        case 24:
            $level = 3;
            break;
        case 25:
            $level = 4;
            break;
    }
?>
<div class="btn-group">
    <a href="<?php echo site_urlc('product/index')?>" class='btn'> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?></a>
</div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
    <h3> <i class="fa fa-pencil"></i> <span class="badge badge-success pull-right"><?php echo $title; ?></span> <?php echo lang('add') ?></h3>
    <?php echo form_open(current_urlc(),array("class"=>"form-horizontal","id"=>"frm-create")); ?>

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
        <?php if($this->cid !=22 ) {?>
            <div class="control-group">
                <label class="control-label" for="title"> 关联上级产品 </label>
                <div class="controls">
                    <select name="pid">
                        <?php if($data) {?>
                            <?php foreach($data as $row) {?>
                                <option value="<?php echo $row['id']?>"><?php echo $row['title']?></option>
                            <?php }?>
                        <?php } else {?>
                            <option value="0">请先添加上级产品</option>
                        <?php }?>

                    </select>
                </div>
            </div>
        <?php }?>

        <!-- 弹出 -->
        <div id="seo-modal" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3> <i class="fa fa-info-circle"></i><?php echo lang('seo') ?> </h3>
            </div>
            <div class="modal-body seamless">

                <div class="control-group">
                    <label for="title_seo" class="control-label"><?php echo lang('title_seo') ?></label>
                    <div class="controls">
                        <input type="text" id="title_seo" name="title_seo" x-webkit-speech>
                        <span class="help-inline"></span>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="tags"><?php echo lang('tag') ?></label>
                    <div class="controls">
                        <input type="text" id="tags" name="tags">
                        <span class="help-inline">使用英文标点`,`隔开</span>
                    </div>
                </div>

                <div class="control-group">
                    <label for="intro"  class="control-label"><?php echo lang('intro') ?></label>
                    <div class="controls">
                        <textarea name="intro" rows='8' class='span4'></textarea>
                        <span class="help-inline"></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#"  data-dismiss="modal" aria-hidden="true" class="btn"><?php echo lang('close') ?></a>
            </div>
        </div>


        <?php if($this->cid != 22) {?>
        <!--<div class="control-group uefull">
            <textarea id="content" name="content" ></textarea>
        </div>-->
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1" data-toggle="tab">ZH简介</a></li>
                <li class=""><a href="#tab2" data-toggle="tab">FR简介</a></li>
                <li class=""><a href="#tab3" data-toggle="tab">ES简介</a></li>
                <li class=""><a href="#tab4" data-toggle="tab">RU简介</a></li>
                <li class=""><a href="#tab5" data-toggle="tab">EN简介</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab1">
                    <div class="control-group uefull">
                        <textarea id="ZH_content" name="ZH_content" ></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="tab2">
                    <div class="control-group uefull">
                        <textarea id="FR_content" name="FR_content" ></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="tab3">
                    <div class="control-group uefull">
                        <textarea id="ES_content" name="ES_content" ></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="tab4">
                    <div class="control-group uefull">
                        <textarea id="RU_content" name="RU_content" ></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="tab5">
                    <div class="control-group uefull">
                        <textarea id="EN_content" name="EN_content" ></textarea>
                    </div>
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
                        <input class="fileupload" type="file"  accept="" multiple="">
                    </span>
                    <input type="hidden" name="photo" class="form-upload" data-more="1" value="">
                    <input type="hidden" name="thumb" class="form-upload-thumb" value="">
                </div>
            </div>
        </div>
        <div id="js-photo-show" class="js-img-list-f"></div>
        <div class="clear"></div>
        <!-- 图片上传 -->
        <?php }?>

    </div>

    <div class="boxed-footer">
        <?php if ($this->ccid): ?>
            <input type="hidden" name="ccid" value="<?php echo $this->ccid ?>">
        <?php endif ?>
        <input type="hidden" name="cid" value="<?php echo $this->cid ?>">
        <input type="hidden" name="level" value="<?php echo $level ?>">
        <input type="submit" value=" <?php echo lang('submit'); ?> " class='btn btn-primary'>
        <input type="reset" value=' <?php echo lang('reset'); ?> ' class="btn btn-danger">
    </div>
</form>
</div>

<?php include_once 'inc_ui_media.php'; ?>
<script type="text/javascript">
    require(['jquery','adminer/js/ui','adminer/js/media'],function($,ui,media){
        ui.editor_create('ZH_content');
        ui.editor_create('FR_content');
        ui.editor_create('ES_content');
        ui.editor_create('RU_content');
        ui.editor_create('EN_content');
        // media 上传
        media.init();
        var products_photos = <?php echo json_encode(one_upload(set_value("photo"))) ?>;
        media.show(products_photos,"photo");
  });
</script>
