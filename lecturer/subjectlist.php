<?php
require '../core.php';

if (!Users::HasAccess('lecturer'))
    Helper::Redirect('/');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UTHM - Subject List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
<?php include_once 'nav.inc'; ?>

<div class="container p-3">
    <h2>Your Subject List</h2>
    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>No</th>
            <th>Subject</th>
            <th>Create Assignment/Tutorial</th>
            <th>Quiz True/False</th>
            <th>Quiz Subjective</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (Subject::RetrieveByLecturer(Users::$Session['id']) as $idx => $row) {
            $idx++;
            echo "<tr>";
            echo "<td>{$idx}</td>";
            echo "<td>{$row['subject_name']}</td>";
            echo "<td class='text-center'><a href='task.php?id={$row['subject_code']}' class='btn btn-info'>Create</a></td>";
            echo "<td class='text-center'><a href='quiz-result.php?id={$row['subject_code']}' class='btn btn-info'>Result</a> <a href='quiz.php?id={$row['subject_code']}' class='btn btn-warning'>Create/View Quiz</a></td>";
            echo "<td class='text-center'><a href='mcq-result.php?id={$row['subject_code']}' class='btn btn-info'>Result</a> <a href='mcq.php?id={$row['subject_code']}' class='btn btn-warning'>Create/View Quiz</a></td>";
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
