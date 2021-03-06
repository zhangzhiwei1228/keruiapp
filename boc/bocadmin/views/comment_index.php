
<div class="boxed">
    <div class="boxed-inner seamless">

<table class="table table-striped table-hover select-list">
    <thead>
        <tr>
            <th class="width-small"><input id='selectbox-all' type="checkbox" > </th>
            <th>图</th>
            <th>评论标题</th>
            <th>评论用户</th>
            <th>评论内容</th>
            <th>回复内容</th>
            <th>更新时间</th>
            <th class="span1">操作</th>
        </tr>
    </thead>
    <tbody class="sort-list">
        <?php foreach ($list as $v):?>
        <tr data-id="<?php echo $v['id'] ?>" data-sort="<?php echo $v['sort_id'] ?>">
            <td><input class="select-it" type="checkbox" value="<?php echo $v['id']; ?>" ></td>
            <td>
                <?php if ($v['thumb']): ?>
                <a class="fancybox-img" href="<?php echo UPLOAD_URL. str_replace('thumbnail/', '', $v['thumb']); ?>" title="<?php echo $v['title'] ?>">
                    <img src="<?php echo UPLOAD_URL.$v['thumb'] ?>" alt="<?php echo $v['title'];?>">
                </a>
                <?php endif ?>
             </td>
            <td> <?php echo $v['title'] ?></td>
            <td> <?php echo $v['nickname'] ?></td>
            <td> <?php echo $v['content'] ?></td>
            <td> <?php echo $v['comment'] ?></td>
            <td> <?php echo  date("Y/m/d H:i:s",$v['timeline']); ?> </td>
            <td>
                <div class="btn-group">
                    <?php include 'inc_ui_flag.php'; ?>
                    <?php include 'inc_ui_audit.php'; ?>
                    <a class='btn btn-small' href=" <?php echo site_urlc( $this->router->class.'/edit/'.$v['id']) ?> " title="<?php echo lang('edit') ?>"> <i class="fa fa-pencil"></i> <?php // echo lang('edit') ?></a>
                    <a class='btn btn-danger btn-small btn-del' data-id="<?php echo $v['id'] ?>" href="#"  title="<?php echo lang('del') ?>"> <i class="fa fa-times"></i> <?php // echo lang('del') ?></a>
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
    <a id="btn-audit" class='btn' href="#" data-audit='1'><?php echo lang('audit') ?></a>
    <a id="btn-audit" class='btn' href="#"  data-audit='0'>取消审核</a>
</div>

<?php echo $pages; ?>

<script>
require(['adminer/js/ui'],function(ui){
  var comment = {
    url_del: "<?php echo site_urlc('comment/delete'); ?>"
    ,url_audit: "<?php echo site_urlc('comment/audit'); ?>"
    ,url_flag: "<?php echo site_urlc('comment/flag'); ?>"
    ,url_sortid: "<?php echo site_urlc('comment/sortid'); ?>"
  };

  ui.fancybox_img();
  ui.btn_delete(comment.url_del);     // 删除
  ui.btn_audit(comment.url_audit);    // 审核
  ui.btn_flag(comment.url_flag);      // 推荐
  ui.sortable(comment.url_sortid);    // 排序
});
</script>
