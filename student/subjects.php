<?php
require '../core.php';

if (!Users::HasAccess('student'))
    Helper::Redirect('/');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subject List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
<?php include_once 'nav.inc'; ?>

<div class="container p-3">
    <h2>Subject List</h2>
    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>No</th>
            <th>Lecturer</th>
            <th>Subject</th>
            <th>Task</th>
            <th><br>Mark</br>T/F</th>
            <th><br>Quiz</br></th>
            <th><br>Mark</br>Subjective</th>
            <th><br>Quiz</br> Subjective</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (Subject::RetrieveRegisteredSubject(Users::$Session['id']) as $idx => $row) {
            $lecturer = Subject::RetrieveSubjectLecturer($row['subject_code'])[0];

            $Quiz = Quiz::RetrieveTFQuestion($row['subject_code']);
            $QuizAnswer = Quiz::RetrieveTFStudentAnswer($row['subject_code'], Users::$Session['id']);

            $MCQ = Quiz::RetrieveMCQQuestion($row['subject_code']);
            $MCQAnswer = Quiz::RetrieveMCQStudentAnswer($row['subject_code'], Users::$Session['id']);

            echo "<tr>";
            echo "<td>" . ++$idx . "</td>";
            echo "<td>{$lecturer['name']}</td>";
            echo "<td>{$row['subject_name']}</td>";
            echo "<td><a href='task.php?subject={$row['subject_code']}' class='btn btn-success'>View</a></td>";
            echo "<td>".($QuizAnswer['correct'] * 2)."</td>";
            echo "<td><a href='quiz.php?subject={$row['subject_code']}' class='btn btn-success'>View</a></td>";
            echo "<td>".($MCQAnswer['correct'] * 2)."</td>";
            echo "<td><a href='mcq.php?subject={$row['subject_code']}' class='btn btn-success'>View</a></td>";
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
