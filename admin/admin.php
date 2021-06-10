<?php
require '../core.php';

if (!Users::HasAccess('admin'))
    Helper::Redirect("/");

if (isset($_POST['id'], $_POST['name'])) {
    $ID = intval(trim($_POST['id']));
    $Name = htmlspecialchars(trim($_POST['name']));

    if (empty($_POST['id']) || empty($Name)) {

    } else {
        if (isset($_GET['edit'])) {
            $OID = intval(trim($_POST['oid']));

            $flag = Users::update('admin', $OID, $ID, $Name);
        } else {
            $flag = Users::register('admin', $ID, $Name);
        }

        if (isset($flag['error'])) {

        } else {
            Helper::Redirect($_SERVER['PHP_SELF']);
        }
    }
}

if (isset($_GET['edit'])) {
    $ID = intval($_GET['edit']);

    $EditData = Users::RetrieveById('admin', $ID);
    if (empty($EditData))
        Helper::Redirect($_SERVER['PHP_SELF']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
<?php include_once 'nav.inc'; ?>

<div class="container p-3">
    <div class="page-header border-bottom mb-3">
        <h2 class="text-center">Register Admin</h2>
    </div>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                <div class="form-group row">
                    <label for="admin_id" class="col-sm-4 col-form-label">Admin ID</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="admin_id" name="id"
                               value="<?php echo isset($_GET['edit']) ? $EditData['id'] : ''; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="admin_name" class="col-sm-4 col-form-label">Admin Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="admin_name" name="name"
                               value="<?php echo isset($_GET['edit']) ? $EditData['name'] : ''; ?>" required>
                    </div>
                </div>

                <div class="text-center">
                    <?php
                    if (isset($_GET['edit'])) { ?>
                        <input type="hidden" name="oid" value="<?php echo $EditData['id'] ?>"/>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-primary">Add</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <hr/>

    <div class="page-header">
        <h2>Admin List</h2>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Admin ID</th>
            <th>Admin Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (Users::RetrieveAll('admin') as $row) {
            echo "<tr>";
            echo "<td>{$row['admin_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td class='text-center'><a href='?edit={$row['admin_id']}' class='btn btn-info'>Update</a> <a href='?delete={$row['admin_id']}' onclick='return confirm(\"Are you sure want to delete this record?\")' class='btn btn-danger'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
</body>
</html>
