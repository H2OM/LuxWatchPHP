<?php $parent = isset($category['childs']);?>
<ul>
    <a href="category/<?=$category['alias'];?>"><?=$category['title'];?></a>
    <?php if(isset($category['childs'])):?>
        <li>
            <?=$this->getMenuHtml($category['childs']);?>
        </li>
    <?php endif;?>
</ul>