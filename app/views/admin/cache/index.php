
 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Order list</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item active">Cache cleaning</li>
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
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>   
                            </thead>
                            <tbody>
                                    <tr>
                                        <td>Categories cache</td>
                                        <td>Site category menu. Cache provided data for 1 hour</td>
                                        <td><a href="<?=ADMIN;?>/cache/delete?key=category" style="color: red; text-decoration:underline;">Clean this cache</a></td>
                                    </tr>
                                    <tr>
                                        <td>Filters cache</td>
                                        <td>Site filters  and groups. Cache provided data for 1 hour</td>
                                        <td><a href="<?=ADMIN;?>/cache/delete?key=filter" style="color: red; text-decoration:underline;">Clean this cache</a></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->