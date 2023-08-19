<?php
    $parent = isset($category['childs']);
    $delete = '<a href="' . ADMIN . '/category/delete?id=' . $id . '" class="delete"><i class="fa fa-fw fa-close text-danger">&#10006;</i></a>';
?>
<p class="item-p">
    <a class="list-group-item" href="<?=ADMIN;?>/category/edit?id=<?=$id;?>&parentId=<?=$category['parent_id'];?>"><?=$category['title'];?></a> <span><?=$delete;?></span>
</p>
<?php if($parent): ?>
    <div class="list-group">
        <?= $this->getMenuHtml($category['childs']); ?>
    </div>
<?php endif; ?>
