
<div class="btn-group">
    <a href="<?php echo site_urlc('language/index')?>" class='btn'> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?></a>
</div>

<?php
$cid = $this->cid ? $this->cid : $_GET['c'];
include_once 'inc_form_errors.php'; ?>

<div class="boxed">
    <h3> <i class="fa fa-plus"></i> <span class="badge badge-success pull-right"><?php echo $title; ?></span> <?php echo lang('add') ?></h3>
    <?php echo form_open(current_urlc(),array("class"=>"form-horizontal","id"=>"frm-create")); ?>

        <div class="boxed-inner seamless">
            <?php if($cid == 18) {?>
                <div class="control-group">
                    <label class="control-label" for="title">
                        <?php  switch($cid) {
                            case 18:
                                $title = '语种';
                                break;
                            case 19:
                                $title = '地区名';
                                break;
                            default:
                                $title = '';
                                break;

                        } echo $title;
                        ?>
                    </label>
                    <div class="controls">
                        <input type="text" id="title" name="title" value="<?php echo set_value("title") ?>" x-webkit-speech>
                        <a href="#seo-modal" role="button" class="btn btn-info" data-toggle="modal"><?php echo lang('seo') ?></a>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="title"> 描述 </label>
                    <div class="controls">
                        <textarea id="content" name="content"><?php echo set_value("content") ?></textarea>
                    </div>
                </div>
            <?php } else {?>
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab6" data-toggle="tab">ZH地区名</a></li>
                        <li class=""><a href="#tab7" data-toggle="tab">FR地区名</a></li>
                        <li class=""><a href="#tab8" data-toggle="tab">ES地区名</a></li>
                        <li class=""><a href="#tab9" data-toggle="tab">RU地区名</a></li>
                        <li class=""><a href="#tab10" data-toggle="tab">EN地区名</a></li>
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
                                <textarea id="content" name="content" ></textarea>
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
            <?php }?>



            <div class="control-group">
                <label for="title" class="control-label">时间:</label>
                <div class="controls">
                    <div class="input-append date timepicker">
                        <input type="text" value="<?php echo date("Y-m-d H:i:s",set_value('timeline',now())); ?>" id="timeline" name="timeline" data-date-format="yyyy-mm-dd hh:ii:ss">
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                </div>
            </div>
               <!-- ctype -->
            <?php if ($ctype = list_coltypes($this->cid)) { ?>
            <div class="control-group">
                <label class="control-label" for="status"> 所属分类:</label>
                <div class="controls">
                    <?php
                        echo ui_btn_select('ctype',set_value("ctype"),$ctype);
                    ?>
                    <span class="help-inline"></span>
                </div>
            </div>
            <?php } ?>
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
                            <input type="text" id="title_seo" name="title_seo" value="<?php echo set_value("title_seo") ?>" x-webkit-speech>
                            <span class="help-inline"></span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="tags"><?php echo lang('tag') ?></label>
                        <div class="controls">
                            <input type="text" id="tags" name="tags" value="<?php echo set_value("tags") ?>">
                            <span class="help-inline">使用英文标点`,`隔开</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="intro"  class="control-label"><?php echo lang('intro') ?></label>
                        <div class="controls">
                            <textarea name="intro" rows='8' class='span4'> <?php echo set_value('intro') ?> </textarea>
                            <span class="help-inline"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#"  data-dismiss="modal" aria-hidden="true" class="btn"><?php echo lang('close') ?></a>
                </div>
            </div>
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
});

</script>
