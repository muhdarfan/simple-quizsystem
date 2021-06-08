<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Workload</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
	<?php include_once 'nav_bar.php'; ?>
 <div class="container-fluid">
   <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <div class="page-header">
        <h2 class="text-center">Assign Workload Lecturer</h2>
      </div>

      <form action="#" method="post" class="form-horizontal">
     <div class="form-group">
          <label for="adminID" class="col-sm-3 control-label">No: </label>
          <div class="col-sm-9">
         <input name="number" type="text" class="form-control" id="number"  value="<?php if(isset($_GET['edit'])) echo $editrow['*']; ?>"required/> <br />
      </div>
        </div>

        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">Lecturer: </label>
            <div class="col-sm-9">
              <select name="lecturer" class="form-control" id="lecturer" required>
              <option value="">Please select</option>
            </select>
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">Subject: </label>
            <div class="col-sm-9">
              <select name="subject" class="form-control" id="subject" required>
              <option value="">Please select</option>
            </select>
            </div>
        </div>

          <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
      <?php if (isset($_GET['edit'])) { ?>
      <input type="hidden" name="oldcid" value="<?php echo $editrow['*']; ?>">
       <button class="btn btn-default" type="submit" name="update"><i class="bi bi-pencil-fill" aria-hidden="true"></i> Update</button>
      <?php } else { ?>
      <button class="btn btn-default" type="submit" name="create"><span class="bi bi-pencil-fill" aria-hidden="true"></span> Create</button>
      <?php } ?>
       <button class="btn btn-default" type="reset"><i class="bi bi-eraser-fill" aria-hidden="true"></i> Clear</button>
       </div>
       </div> 
      </form>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="page-header">
        <h2>Lecturer List</h2>
      </div>
      <table class="table table-striped table-bordered">
        <tr>
          <th>No</th>
          <th>Lecturer</th>
          <th>Subject</th>
          <th>Action</th>
        </tr>




<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>
</html>