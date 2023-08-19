 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Categories</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>/category">Categories</a></li>
              <li class="breadcrumb-item active">New category</li>
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
            <div class="box" style="width: 100%;">
                
                    <form action="<?=ADMIN;?>/category/add" method="post">
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Category title</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Category title" required>
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Parent category</label>
                                <?php new app\widgets\menu\Menu([
                                    'tpl'=> DIR . '/menu/select.php',
                                    'container' => 'select',
                                    'cache'=>0,
                                    'cacheKey'=>'admin_select',
                                    'class'=>'form-control',
                                    'attrs'=>[
                                        'name'=>'parent_id',
                                        'id'=>'parent_id'
                                    ],
                                    'prepend'=>'<option value="0">Single category</option>'
                                ])?>
                            </div>
                            <div class="form-group">
                                <label for="keywords">Category keywords</label>
                                <input type="text" name="keywords" class="form-control" id="title" placeholder="Category keywords">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" name="description" class="form-control" id="description" placeholder="Description">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Add</button>
                        </div>
                    </form>
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->