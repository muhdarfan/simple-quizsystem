<?php
require '../core.php';

if (!Users::HasAccess('lecturer'))
    Helper::Redirect('/');

if (!isset($_GET['id']))
    Helper::Redirect("subjectlist.php");

$SubjectCode = htmlspecialchars($_GET['id']);
$Subject = Subject::Retrieve($SubjectCode);

if (isset($Subject['error']) || empty($Subject))
    Helper::Redirect("subjectlist.php");

if (isset($_POST['name'], $_FILES['file'])) {
    $Name = htmlspecialchars(trim($_POST['name']));

    if (empty($Name)) {
        Helper::$Error['task'][] = "Please fill in the blanks.";
    } else {
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $array = explode('.', $_FILES['file']['name']);
        $file_ext = strtolower(end($array));

        if ($file_size > 2097152)
            Helper::$Error['task'][] = "File size must be exactly 2 MB";

        if (empty(Helper::$Error['task'])) {
            $flag = Subject::AddAssignment($SubjectCode, $Name, $file_name);

            if (isset($flag['error'])) {
                Helper::$Error['task'] = $flag['error'];
            } else {
                move_uploaded_file($file_tmp, BASE . "/uploads/" . $file_name);
                Helper::Redirect($_SERVER['REQUEST_URI']);
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $TaskID = intval($_GET['delete']);
    $flag = Subject::DeleteAssignment($TaskID);

    if (!isset($flag['error'])) {
        Helper::Redirect($_SERVER['PHP_SELF']."?id={$SubjectCode}");
    } else {
        Helper::$Error['task'][] = $flag['error'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>

<?php include_once 'nav.inc'; ?>

<div class="container p-3">
    <div class="page-header border-bottom mb-3">
        <h2 class="text-center">Create Task</h2>
    </div>

    <?php
    if (!empty(Helper::$Error['task'])) {
        ?>
        <div class="alert alert-danger" role="alert">
            <ul>
                <?php
                foreach (Helper::$Error['task'] as $err) {
                    echo "<li>{$err}</li>";
                }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>


    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="subjectName" class="col-sm-4 col-form-label">Subject Name</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="subjectName"
                               value="<?php echo $Subject['name'] ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="subjectCode" class="col-sm-4 col-form-label">Subject Code</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="subjectCode"
                               value="<?php echo $Subject['subject_code'] ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="assignment" class="col-sm-4 col-form-label">Assignment/Tutorial Lab</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="assignment" name="name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputFile" class="col-sm-4 col-form-label">File</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control-file" id="inputFile" name="file">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <hr/>

    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>No</th>
            <th>Assignment/Tutorial/Lab</th>
            <th>File Name</th>
            <th>Modified By</th>
            <th>Modified On</th>
            <th>View Content</th>
            <th>View Submission</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (Subject::RetrieveAssignment($SubjectCode) as $idx => $row) {
            $idx++;
            echo "<tr>";
            echo "<td>{$idx}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['file_name']}</td>";
            echo "<td>{$row['updated_by']}</td>";
            echo "<td>{$row['updated_at']}</td>";
            echo "<td class='text-center'><a href='/uploads/{$row['file_name']}' target='_blank' class='btn btn-info'>View</a></td>";
            echo "<td class='text-center'><a href='submission.php?id={$row['id']}' class='btn btn-warning'>Submission</a></td>";
            echo "<td class='text-center'><a href='?id={$SubjectCode}&delete={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure want to delete this data?\");'>Delete</a></td>";
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
