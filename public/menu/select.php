<option value="<?=$id;?>" 
    <?=(isset($_GET['parentId']) && $id==$_GET['parentId']) ? "selected": "";?>
    <?=$cond;?>
    ><?=$tab . $category['title'];?>
</option>
<?php if(isset($category['childs'])):?>
    <?=$this->getMenuHtml($category['childs'], '&nbsp;' . $tab, '-')?>
<?php endif;?>