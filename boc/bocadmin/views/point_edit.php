
<div class="btn-group"><a href="<?php echo site_urlc('point/index');?>" class="btn"> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?> </a></div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
    <h3> <i class="fa fa-pencil"></i> <?php echo lang('edit') ?> <span class="badge badge-success pull-right"><?php echo $title; ?></span></h3>
    <?php echo form_open(current_urlc(), array('class' => 'form-horizontal', 'id' => 'frm-edit')); ?>

    <div class="boxed-inner seamless">

        <div class="control-group">
            <label for="title" class="control-label">标识:</label>
            <div class="controls">
                <input type="text" name="title" id="title" value="<?php echo set_value('title',$it['title']); ?>"  class='span7' <?php echo ENVIRONMENT != 'production' ? '' : 'readonly'   ?>>
                <a href="#seo-modal" role="button" class="btn btn-info" data-toggle="modal"><?php echo lang('seo') ?></a>
                <span class="help-inline"></span>
            </div>
        </div>

        <!-- 弹出 -->
        <div id="seo-modal" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h3> <i class="fa fa-info-circle"></i> <?php echo lang('seo') ?></h3>
            </div>
            <div class="modal-body seamless">

                <div class="control-group">
                    <label for="title_seo" class="control-label"><?php echo lang('title_seo') ?></label>
                    <div class="controls">
                        <input type="text" id="title_seo" name="title_seo" value="<?php echo set_value('title_seo',$it['title_seo']) ?>" x-webkit-speech>
                        <span class="help-inline"></span>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="tag"><?php echo lang('tag') ?></label>
                    <div class="controls">
                        <input type="text" id="tags" name="tags" value="<?php echo set_value('tags',$it['tags']) ?>" placeholder="tag1,tag2">
                        <span class="help-inline">使用英文标点`,`隔开</span>
                    </div>
                </div>

                <div class="control-group">
                    <label for="intro"  class="control-label"><?php echo lang('intro') ?></label>
                    <div class="controls">
                        <textarea name="intro" rows='8' class='span4'><?php echo set_value('intro',$it['intro']) ?></textarea>
                        <span class="help-inline"></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#"  data-dismiss="modal" aria-hidden="true" class="btn">Close</a>
            </div>
        </div>


        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1" data-toggle="tab">ZH提示语</a></li>
                <li class=""><a href="#tab2" data-toggle="tab">FR提示语</a></li>
                <li class=""><a href="#tab3" data-toggle="tab">ES提示语</a></li>
                <li class=""><a href="#tab4" data-toggle="tab">RU提示语</a></li>
                <li class=""><a href="#tab5" data-toggle="tab">EN提示语</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab1">
                    <div class="control-group uefull">
                        <textarea id="ZH_content" name="ZH_content"> <?php echo set_value('ZH_content',$it['ZH_content']); ?></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="tab2">
                    <div class="control-group uefull">
                        <textarea id="FR_content" name="FR_content"> <?php echo set_value('FR_content',$it['FR_content']); ?></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="tab3">
                    <div class="control-group uefull">
                        <textarea id="ES_content" name="ES_content"> <?php echo set_value('ES_content',$it['ES_content']); ?></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="tab4">
                    <div class="control-group uefull">
                        <textarea id="RU_content" name="RU_content"> <?php echo set_value('RU_content',$it['RU_content']); ?></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="tab5">
                    <div class="control-group uefull">
                        <textarea id="EN_content" name="EN_content"> <?php echo set_value('EN_content',$it['EN_content']); ?></textarea>
                    </div>
                </div>
            </div>
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
    require(['jquery','adminer/js/ui'],function($,ui){
        ui.editor_create('ZH_content');
        ui.editor_create('FR_content');
        ui.editor_create('ES_content');
        ui.editor_create('RU_content');
        ui.editor_create('EN_content');
    });
</script>
