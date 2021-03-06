<div class="btn-group">
    <a href="<?php echo site_urlc('language/create')?>" class='btn btn-primary'> <i class="fa fa-plus"></i> <?php echo $title; ?> </a>
</div>

<?php include_once 'inc_modules_path.php'; ?>
<div class="clearfix"><p></p></div>

<div class="boxed">
    <div class="boxed-inner seamless">
<table class="table table-striped table-hover select-list">
    <thead>
        <tr>
            <th class="width-small"><input id='selectbox-all' type="checkbox" > </th>
            <th>
                <?php $cid = $this->cid ? $this->cid : $_GET['c']; switch($cid) {
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
                ?></th>
            <th>描述</th>
            <th class="span1">操作</th>
        </tr>
    </thead>
    <tbody class="sort-list">
        <?php foreach ($list as $v):?>
        <tr data-id="<?php echo $v['id'] ?>" data-sort="<?php echo $v['sort_id'] ?>">
            <td><input class="select-it" type="checkbox" value="<?php echo $v['id']; ?>" ></td>

            <td><?php echo msubstr($v['title'],0,20); ?></td>
            <td><?php echo msubstr($v['content'],0,20); ?></td>
            <td>
                <div class="btn-group">
                    <?php include 'inc_ui_audit.php'; ?>
                    <a class='btn  btn-danger btn-small' href=" <?php echo site_urlc( $this->router->class.'/edit/'.$v['id']) ?> " title="<?php echo lang('edit') ?>"> <i class="fa fa-pencil"></i> <?php // echo lang('edit') ?></a>
                    <?php if($cid != 19) {?>
                    <a class='btn btn-small btn-del' data-id="<?php echo $v['id'] ?>" href="#"  title="<?php echo lang('del') ?>"> <i class="fa fa-times"></i> <?php // echo lang('del') ?></a>
                    <?php }?>
                </div>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

    </div>
</div>

<div class="btn-group">
    <a id='select-all' class='btn' href="#"> <i class=""></i> <?php echo lang('select_all') ?> </a>
    <a id='unselect-all' class='btn hide' href="#"> <i class=""></i> <?php echo lang('unselect') ?> </a>
    <a id="btn-del" class='btn btn-danger' href="#"> <i class="fa fa-times"></i> <?php echo lang('del') ?> </a>
    <!--<a id="btn-audit" class='btn' href="#" data-audit='1'><?php /*echo lang('audit') */?></a>
    <a id="btn-audit" class='btn' href="#"  data-audit='0'>取消审核</a>-->
</div>

<?php echo $pages; ?>

<script>
require(['adminer/js/ui'],function(ui){
    var language = {
        url_del: "<?php echo site_urlc('language/delete'); ?>"
        ,url_audit: "<?php echo site_urlc('language/audit'); ?>"
        ,url_flag: "<?php echo site_urlc('language/flag'); ?>"
        ,url_sortid: "<?php echo site_urlc('language/sortid'); ?>"
        ,url_sort_change: "<?php echo site_urlc('language/sort_change'); ?>"
        ,url_copy: "<?php echo site_urlc('language/copypro'); ?>" 
    };
    ui.fancybox_img();
    ui.btn_delete(language.url_del);     // 删除
    ui.btn_audit(language.url_audit);    // 审核
    ui.btn_flag(language.url_flag);      // 推荐
    ui.sortable(language.url_sortid); // 排序  拖动排序和序号排序在firefox中有bug
    ui.sort_change(language.url_sort_change); // input 排序
    ui.btn_copy(language.url_copy);    // 热门审核
});
</script>
