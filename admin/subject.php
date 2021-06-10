<?php
require '../core.php';

if(!Users::HasAccess('admin'))
    Helper::Redirect("/");

$Err = Array();

if (isset($_POST['subject_code'], $_POST['subject_name'])) {
    $Code = htmlspecialchars($_POST['subject_code']);
    $Name = htmlspecialchars($_POST['subject_name']);

    if (empty($Code) || empty($Name)) {

    } else {
        if (isset($_GET['edit'])) {
            // EDIT
        } else {
            // ADD
            $flag = Subject::AddSubject($Code, $Name);

            if (isset($flag['error'])) {
                // error
                $Err[] = $flag['error'];
            } else {
                Helper::Redirect($_SERVER['PHP_SELF']);
            }
        }
    }
}

// Delete
if (isset($_GET['action'], $_GET['id'])) {
    if ($_GET['action'] == 'delete') {
        $code = htmlspecialchars($_GET['id']);

        $flag = Subject::DeleteSubject($code);

        if (isset($flag['error'])) {

        } else {
            Helper::Redirect($_SERVER['PHP_SELF']);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UHTM - Subject</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>

<?php include_once 'nav.inc'; ?>

<div class="container p-3">
    <div class="page-header border-bottom mb-3">
        <h2 class="text-center">Register Subject</h2>
    </div>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                <div class="form-group row">
                    <label for="subjectCode" class="col-sm-4 col-form-label">Subject Code</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="subjectCode" name="subject_code" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="subjectName" class="col-sm-4 col-form-label">Subject Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="subjectName" name="subject_name" required>
                    </div>
                </div>

                <div class="text-center">
                    <?php
                    if (isset($_GET['edit'])) { ?>
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
        <h2>Subject List</h2>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th>Modified By</th>
            <th>Modified On</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (Subject::Retrieve() as $row) {
            echo "<tr>";
            echo "<td>{$row['subject_code']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['updated_by']}</td>";
            echo "<td>{$row['updated_at']}</td>";
            echo "<td class='text-center'><a class='btn btn-info'>Update</a> <a href='?action=delete&id={$row['subject_code']}' class='btn btn-danger' onclick='return confirm(\"Are you sure want to delete this data?\");'>Delete</a></td>";
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
