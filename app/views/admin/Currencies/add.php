 <!-- Content Header (Page header) -->
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add new currrency</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?=ADMIN;?>/currencies">Currencies</a></li>
              <li class="breadcrumb-item active">Add new currency</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="padding-bottom: 10px;">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="box" style="width: 100%;">
                <form action="<?=ADMIN;?>/currencies/add" method="post">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input type="text" class="form-control" name="code" id="code" required>
                        </div>
                        <div class="form-group">
                            <label for="value">Value</label>
                            <input type="number" step="0.01" class="form-control" name="value" id="value" required>
                        </div>
                        <div class="form-group">
                            <label for="symbol">Symbol</label>
                            <input type="text" class="form-control" name="symbol" id="symbol">
                        </div>
                        <div class="form-group">
                            <label for="base">Base</label>
                            <input type="checkbox" name="base" id="base">
                        </div>
                        <div class="form-group">
                            <label for="symbolpos">
                               Left <input type="radio" class="form-control" name="symbolpos" id="symbolpos" value="left" style="width: 20px; height:20px; " > 
                               Right <input type="radio" class="form-control" name="symbolpos" id="symbolpos" value="right" style="width: 20px; height:20px; " > 
                            </label>
                        </div>
                   </div>
                   <div class="box-footer" style="width:100%">
                        <button type="submit" class="btn btn-primary" style="width:100%">Save</button>
                   </div>
                </form>    
            </div>
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->