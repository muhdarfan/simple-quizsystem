<?php
require '../core.php';

if (!Users::HasAccess('student'))
    Helper::Redirect('/');

if (!isset($_GET['task']))
    Helper::Redirect("subjects.php");

$Task = Subject::RetrieveAssignmentById($_GET['task']);
$Submission = Subject::RetrieveStudentSubmission($_GET['task'], Users::$Session['id']);

if (isset($Task['error']) || empty($Task))
    Helper::Redirect("subjects.php");

if (isset($_FILES['file'])) {
    if ($_FILES['file']['error'] != 0) {
        Helper::$Error['task'][] = "An error has been occured while uploading the file. [Err: {$_FILES['file']['error']}]";
    } else {
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $array = explode('.', $_FILES['file']['name']);
        $file_ext = strtolower(end($array));

        if ($file_size > 2097152)
            Helper::$Error['task'][] = "File size must be below than 2 MB";

        if (empty(Helper::$Error['task'])) {
            $flag = Subject::AddSubmission($Task['id'], Users::$Session['id'], $file_name);

            if (isset($flag['error'])) {
                Helper::$Error['task'][] = $flag['error'];
            } else {
                move_uploaded_file($file_tmp, BASE . "/uploads/submission/" . $file_name);
                Helper::Redirect("task.php?subject=" . $Task['subject_code']);
            }
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
    <title>UTHM - Submit Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <style>
        .col-form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php include_once 'nav.inc'; ?>

<div class="container p-3">
    <div class="pb-2 mt-4 mb-2 border-bottom text-center">
        <h2>Task Submission</h2>
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
        <div class="col-md-8 offset-md-2">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="subjectName" class="col-sm-4 col-form-label">Subject Name</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="subjectName"
                               value="<?php echo $Task['subject_name'] ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="subjectCode" class="col-sm-4 col-form-label">Subject Code</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="subjectCode"
                               value="<?php echo $Task['subject_code'] ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="taskName" class="col-sm-4 col-form-label">Assignment/Tutorial/Lab</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="taskName"
                               value="<?php echo $Task['name'] ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputFile" class="col-sm-4 col-form-label">File</label>
                    <div class="col-sm-8">
                        <?php

                        if (!isset($Submission['file_name']))
                            echo '<input type="file" class="form-control-file" id="inputFile" name="file">';
                        else
                            echo '<input type="text" readonly class="form-control-plaintext" id="inputFile" value="' . $Submission['file_name'] . '" required>';
                        ?>
                    </div>
                </div>

                <div class="text-center">
                    <?php
                    if (!isset($Submission['file_name']))
                        echo '<button type="submit" class="btn btn-success">Upload</button>';
                    else
                        echo '<a href="task.php?subject=' . $Task['subject_code'] . '" class="btn btn-primary">Back</a>';
                    ?>
                </div>
            </form>
        </div>
    </div>

    <hr/>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
</body>
</html>
