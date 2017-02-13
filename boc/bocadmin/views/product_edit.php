
<div class="btn-group"><a href="<?php echo site_urlc('product/index');?>" class="btn"> <i class="fa fa-arrow-left"></i> <?php echo lang('back_list')?> </a></div>

<?php include_once 'inc_form_errors.php'; ?>

<div class="boxed">
    <h3> <i class="fa fa-pencil"></i> <?php echo lang('edit') ?> <span class="badge badge-success pull-right"><?php echo $title; ?></span></h3>
    <?php echo form_open(current_urlc(), array('class' => 'form-horizontal', 'id' => 'frm-edit')); ?>

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

                            <input type="text" id="title" value="<?php echo set_value('title',$it['title']); ?>" name="title" class='span7'>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab7">
                    <div class="control-group">

                        <div class="controls">
                            <input type="text" id="FR_title" value="<?php echo set_value('FR_title',$it['FR_title']); ?>" name="FR_title" class='span7'>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab8">
                    <div class="control-group">

                        <div class="controls">
                            <input type="text" id="ES_title" value="<?php echo set_value('ES_title',$it['ES_title']); ?>" name="ES_title" class='span7'>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab9">
                    <div class="control-group">

                        <div class="controls">
                            <input type="text" id="RU_title" value="<?php echo set_value('RU_title',$it['RU_title']); ?>" name="RU_title" class='span7'>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab10">
                    <div class="control-group">
                        <div class="controls">
                            <input type="text" id="EN_title" value="<?php echo set_value('EN_title',$it['EN_title']); ?>" name="EN_title" class='span7'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($this->cid !=22 ) {?>
            <div class="control-group">
                <label class="control-label" for="title"> 关联上级产品 </label>
                <div class="controls">
                    <!--<select name="pid">
                        <?php /*if($data) {*/?>
                            <?php /*foreach($data as $row) {*/?>
                                <option value="<?php /*echo $row['id']*/?>" <?php /*echo $it['pid'] == $row['id'] ? 'selected' : '' */?>><?php /*echo $row['title']*/?></option>
                            <?php /*}*/?>
                        <?php /*} else {*/?>
                            <option value="0">请先添加上级产品</option>
                        <?php /*}*/?>
                    </select>-->
                    <?php if($this->cid == 23) {?>
                        <select name="pid">
                            <?php if($data) {?>
                                <?php foreach($data as $row) {?>
                                    <option value="<?php echo $row['id']?>" <?php echo $row['id'] == $it['pid'] ? 'selected' : '' ?>><?php echo $row['title']?></option>
                                <?php }?>
                            <?php } else {?>
                                <option value="0">请先添加上级产品</option>
                            <?php }?>
                        </select>
                    <?php } elseif($this->cid == 24 || $this->cid == 25) {?>
                        <select name="pid" id="first">

                        </select>
                        <select name="pid" id="second">

                        </select>
                        <?php if($this->cid == 25) {?>
                            <select name="pid" id="three">

                            </select>
                        <?php }?>
                    <?php } ?>
                </div>
            </div>
        <?php }?>
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

        <?php if($this->cid != 22) {?>
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
        <!-- 图片上传 -->
        <div class="control-group">
            <label for="img" class="control-label"><?php echo lang('photo') ?></label>
            <div class="controls">
                <div class="btn-group">
                    <span class="btn btn-success">
                        <i class="fa fa-upload"></i>
                        <span> <?php echo lang('upload_file') ?> </span>
                        <input class="fileupload" type="file"  accept="" multiple="">
                    </span>
                    <input type="hidden" name="photo" class="form-upload" data-more="1" value="<?php echo $it['photo'] ?>">
                    <input type="hidden" name="thumb" class="form-upload-thumb" value="<?php echo $it['thumb'] ?>">
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
        <input type="hidden" name="cid" id="cid" value="<?php echo $this->cid ?>">
        <input type="hidden" name="id" value="<?php echo $it['id']?>">
        <input type="submit" value="<?php echo lang('submit') ?>" class="btn btn-primary">
        <input type="reset" value="<?php echo lang('reset') ?>" class="btn btn-danger">
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
        var products_photos = <?php echo json_encode(one_upload($it['photo'])) ?>;
        media.show(products_photos,"photo");

        var cid = '<?php echo $this->cid?>';
        var url = '<?php echo site_url('product/childs/')?>';
        var pid = '<?php echo $it['pid']?>';
        var count = '<?php echo $ids_count?>';
        if(count == 3) {
            var fid1 = '<?php echo $ids ? (isset($ids[2]) && $ids[2] ? $ids[2] : 0) : 0?>';
            var fid2 = '<?php echo $ids ? (isset($ids[1]) && $ids[1] ? $ids[1] : 0) : 0?>';
            var fid3 = '<?php echo $ids ? (isset($ids[0]) && $ids[0] ? $ids[0] : 0) : 0?>';
        }
        if(count == 2) {
            var fid1 = '<?php echo $ids ? (isset($ids[1]) && $ids[1] ? $ids[1] : 0) : 0?>';
            var fid2 = '<?php echo $ids ? (isset($ids[0]) && $ids[0] ? $ids[0] : 0) : 0?>';
        }
        if(count == 1) {
            var fid1 = '<?php echo $ids ? (isset($ids[0]) && $ids[0] ? $ids[0] : 0) : 0?>';
        }
        if(count == 0) {
            var fid1 = pid;
        }
        $.getJSON(url,{pid:0,c:cid}).done(function(rs){
            var html1='';
            if(rs) {
                html1 += '<option class="option" value="-1">请先选择一级产品</option>';
                $.each(rs, function( key, value ) {
                    if(value.id == fid1) {
                        html1+='<option class="option" selected value="'+value.id+'">'+value.title+'</option>';
                    } else {
                        html1+='<option class="option" value="'+value.id+'">'+value.title+'</option>';
                    }
                });
            } else {
                html1+='<option class="option" value="0">请先添加一级产品</option>';
            }
            $('#first').html(html1);
            if(count > 0) {
                pro_change(fid1);
            }

        });
        function p_change(id) {
            if(id!=-1){
                $.getJSON(url,{pid:id,c:cid}).done(function(rs){
                    var html1='';
                    if(rs) {
                        html1 += '<option class="option" value="-1">请先选择二级产品</option>';
                        $.each(rs, function( key, value ) {
                            if(fid2 == value.id) {
                                html1+='<option class="option" selected value="'+value.id+'">'+value.title+'</option>';
                            } else {
                                html1+='<option class="option" value="'+value.id+'">'+value.title+'</option>';
                            }

                        });

                    } else {
                        html1+='<option class="option" value="0">请先添加二级产品</option>';
                    }

                    if(count == 3 || count == 2) {
                        $('#cid').val(cid -1);
                        html1+='<option class="option" value="'+id+'">升到上级</option>';
                    }
                    $('#second').html(html1);
                    pro_change1(fid2);
                })
            }
        }
        function pro_change(fid1){
            if(fid1) {
                p_change(fid1);
            }
            $('#first').on('change',function(){
                var id=$(this).find('option:selected').val();
                p_change(id);
            })
        }
        function p_change1(id) {
            if(id!=-1){
                $.getJSON(url,{pid:id,c:cid}).done(function(rs){
                    var html1='';
                    if(rs) {
                        $.each(rs, function( key, value ) {
                            if(fid3 == value.id) {
                                html1+='<option class="option" selected value="'+value.id+'">'+value.title+'</option>';
                            } else {
                                html1+='<option class="option" value="'+value.id+'">'+value.title+'</option>';
                            }
                        });

                    } else {
                        html1+='<option class="option" value="0">请先添加三级产品</option>';
                    }
                    if(count == 3) {
                        $('#cid').val(cid -1);
                        html1+='<option class="option" value="'+id+'">升到上级</option>';
                    }
                    $('#three').html(html1);
                })
            }
        }
        function pro_change1(fid2){
            if(fid2) {
                p_change1(fid2);
            }
            $('#second').on('change',function(){
                var id=$(this).find('option:selected').val();
                p_change1(id);
            })
        }
    });
</script>
