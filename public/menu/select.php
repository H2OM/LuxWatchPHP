<option value="<?=$id;?>" 
    <?=(isset($_GET['parentId']) && $id==$_GET['parentId']) ? "selected": "";?>
    <?=(isset($_GET['id']) && $id==$_GET['id']) ? "disabled style='background-color: #c9c9c9; color:#9b9999;'": "";?>
    ><?=$tab . $category['title'];?>
</option>
<?php if(isset($category['childs'])):?>
    <?=$this->getMenuHtml($category['childs'], '&nbsp;' . $tab, '-')?>
<?php endif;?>