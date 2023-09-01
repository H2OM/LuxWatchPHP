<?php
    $filters = $data['filters'];
    $groups = $data['groups'];
?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Filters</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item active">Filters</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="card card-primary" style="margin-bottom: 20px; width:100%;">
                <div class="card-header">
                    <h3 class="card-title">Add new filter</h3>
                </div>
                <form action="<?=ADMIN;?>/filter/filter-add" method="post">
                    <div class="card-body">
                        <div class="form-group has-feedback">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Filter title" required>
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Group</label>
                            <?php 
                                $menu = new app\widgets\menu\Menu([
                                    'tpl' => DIR . '/menu/select.php',
                                    'container' => 'select',
                                    'class' => 'form-control',
                                    'attrs' => [
                                        'name' => 'group',
                                        'id' => 'group_Id'
                                    ]
                                ], false);
                                $menu->menuHtml = $menu->getMenuHtml($groups);
                                $menu->output();
                            ?>
                        </div>
                    </div>
                    <div class="card-footer" style="width:100%">
                        <button type="submit" class="btn btn-success" style="width:100%">Add</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="box" style="width: 100%;">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Group</th>
                                    <th>Action</th>
                                </tr>   
                            </thead>
                            <tbody class="filter-group">
                                <?php foreach($filters as $filter):?>
                                    <?php if(!empty($filter['filter'])):?>
                                        <tr data-type="filter">
                                            <td data-editer data-filter="<?=$filter['filter'];?>"><?=$filter['filter'];?></td>
                                            <td data-editer data-group="<?=$filter['group'];?>"><?=$filter['group'];?></td>
                                            <td><button class="groupEditer" style="background-color: unset; border:none;"><i class="fa fa-fw fa-pen"></i></button></td>
                                            <td><a href="<?=ADMIN;?>/filter/filters-cleaning?path=filter&id=<?=$filter['id'];?>" style="color: red; text-decoration:underline;">Delete</a></td>
                                        </tr> 
                                    <?php endif;?>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>