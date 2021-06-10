<?php
require '../core.php';

if (!Users::HasAccess('lecturer'))
    Helper::Redirect('/');

if (!isset($_GET['id']))
    Helper::Redirect("subjectlist.php");

$Task = Subject::RetrieveAssignmentById($_GET['id']);
if (isset($Task['error']) || empty($Task))
    Helper::Redirect("subjectlist.php");
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
    <div class="pb-2 mt-4 mb-2 border-bottom">
        <h5>Subject Name: <?php echo $Task['subject_name']; ?></h5>
        <h5>Subject Code: <?php echo $Task['subject_code']; ?></h5>
        <h5>Assignment/Tutorial/Lab: <?php echo $Task['name']; ?></h5>
    </div>

    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>No</th>
            <th>Student Name</th>
            <th>Student Matric Number</th>
            <th>File Name</th>
            <th>View Content</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (Subject::RetrieveSubmission($Task['id']) as $idx => $row) {
            echo '<tr>';
            echo '<td>'.++$idx.'</td>';
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['student_id']}</td>";
            echo "<td>{$row['file_name']}</td>";
            echo "<td><a target='_blank' href='/uploads/submission/{$row['file_name']}' class='btn btn-primary'>View</a></td>";
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
