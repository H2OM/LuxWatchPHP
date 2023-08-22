
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Filters</h3>
                
                <ul class="nav nav-pills ml-auto p-2">
                    <?php $i = 0;?>
                    <?php foreach($this->groups as $key=>$group):?>
                        <?php $i++;?>
                        <li class="nav-item"><a class="nav-link <?=$i == 1 ? "active" : "";?>" href="#group_<?=$i;?>" data-toggle="tab"><?=$group;?></a></li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <?php $i = 0;?>
                    <?php foreach($this->attrs as $group=>$attrs):?>
                        <?php $i++;?>
                        <div class="tab-pane <?=$i == 1 ? "active" : "";?>" id="group_<?=$i;?>">
                            <?php foreach($attrs as $key=>$attr):?>
                                <?php 
                                    if(!empty($this->filter && in_array($key, $this->filter))) {
                                        $checked = "checked";
                                    } else $checked = "";
                                ?>
                                <label style="display: block;">
                                <input class="filter_input" type="radio" value="<?=$key;?>" name="attrs[<?=$group;?>]" <?=$checked;?>><i></i> <?=$attr;?>
                                </label>
                            <?php endforeach;?>
                        </div>
                    <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>