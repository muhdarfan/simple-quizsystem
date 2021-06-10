<?php
require '../core.php';

if (!Users::HasAccess('student'))
    Helper::Redirect('/');

if (!isset($_GET['subject']))
    Helper::Redirect("subjects.php");

$SubjectCode = htmlspecialchars($_GET['subject']);
$Subject = Subject::Retrieve($SubjectCode);

if (isset($Subject['error']) || empty($Subject))
    Helper::Redirect("subjects.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UTHM - Task List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>

<?php include_once 'nav.inc'; ?>

<div class="container p-3">
    <div class="pb-2 mt-4 mb-2 border-bottom">
        <h5>Subject Name: <?php echo $Subject['name']; ?></h5>
        <h5>Subject Code: <?php echo $Subject['subject_code']; ?></h5>
    </div>

    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>No</th>
            <th>Assignment/Tutorial/Lab</th>
            <th>Your File</th>
            <th>Download Task</th>
            <th>Submission</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (Subject::RetrieveAssignment($SubjectCode) as $idx => $row) {
            $Submission = Subject::RetrieveStudentSubmission($row['id'], Users::$Session['id']);

            echo '<tr>';
            echo '<td>' . ++$idx . '</td>';
            echo "<td>{$row['name']}</td>";
            echo "<td>" . (isset($Submission['file_name']) ? $Submission['file_name'] : 'No Submission') . "</td>";
            echo "<td class='text-center'><a class='btn btn-info' target='_blank' href='/uploads/{$row['file_name']}'>Download</a></td>";
            echo "<td class='text-center'><a class='btn btn-success " . (isset($Submission['file_name']) ? 'disabled' : '') . "' href='submission.php?subject={$SubjectCode}&task={$row['id']}'>Submit</a></td>";
            echo '</tr>';
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
