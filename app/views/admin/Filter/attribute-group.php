<?php
    $attrs_group = $data['attrs_group'];
?>
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Filters groups</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item active">Filters groups</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="card card-primary" style="margin-bottom: 20px; width:100%;">
              <div class="card-header">
                  <h3 class="card-title">Add new group</h3>
              </div>
              <form action="<?=ADMIN;?>/filter/group-add" method="post">
                  <div class="card-body">
                      <div class="form-group has-feedback">
                          <label for="title">Title</label>
                          <input type="text" name="title" class="form-control" id="title" placeholder="Group title" required>
                      </div>
                  </div>
                  <div class="card-footer">
                      <button type="submit" class="btn btn-success" style="width:100%;"><i class="fa fa-fw fa-plus"></i>Add new group</button>
                  </div>
              </form>
            </div>
        </div>
        <div class="row">
            <div class="box" style="width: 100%;">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Filters count</th>
                                    <th>Edit</th>
                                    <th>Delete <small class="text-danger">(If there are filters in the group, they will also be deleted)</small></th>
                                </tr>   
                            </thead>
                            <tbody class="filter-group">
                                <?php foreach($attrs_group as $item):?>
                                    <tr data-type="group">
                                        <td data-editer data-filter="<?=$item['title'];?>"><?=$item['title'];?></td>
                                        <td data-editer><?=$item['filters'];?></td>
                                        <td><button class="groupEditer" style="background-color: unset; border:none;"><i class="fa fa-fw fa-pen"></i></button></td>
                                        <td><a href="<?=ADMIN;?>/filter/filters-cleaning?path=group&id=<?=$item['id'];?>" style="color: red; text-decoration:underline;">Delete</a></td>
                                    </tr> 
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