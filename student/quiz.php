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

// QUESTION & ANSWER VARIABLE
$Question = Quiz::RetrieveTFQuestion($SubjectCode);
$Answer = Quiz::RetrieveTFStudentAnswer($SubjectCode, Users::$Session['id']);

if (isset($_POST['answer'])) {
    foreach ($_POST['answer'] as $idx => $ans) {
        Quiz::AddStudentTFAnswer($idx, Users::$Session['id'], $ans);
    }

    Helper::Redirect($_SERVER['REQUEST_URI']);
}

if (isset($_GET['attempt'])) {
    if ($Answer['correct'] == 0) {
        $flag = Quiz::ReAttemptTF($SubjectCode, Users::$Session['id']);

        if (!isset($flag['error'])) {
            Helper::Redirect($_SERVER['PHP_SELF'] . '?subject=' . $SubjectCode);
        } else {
            Helper::$Error['quiz'][] = $flag['error'];
        }
    } else {
        Helper::$Error['quiz'][] = "Sorry, but you can't re-attempt this quiz.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UTHM - Quiz</title>
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

    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" name="quiz" method="POST">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Question</th>
                <th>True or False</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($Question as $idx => $row) {
                $disabled = ($Answer['answered'] ? 'disabled' : '');
                $qid = $row['id'];

                echo "<tr>";
                echo "<td>" . ++$idx . "</td>";
                echo "<td>{$row['question']}</td>";
                echo '<td><div class="form-check">
<input class="form-check-input" type="radio" name="answer[' . $row['id'] . ']" id="inputAnswer-t-' . $idx . '" value="1" ' . $disabled . ' ' . ($Answer['answered'] && isset($Answer['data'][$qid]) && $Answer['data'][$qid]['choice'] == '1' ? 'checked' : '') . '>
<label class="form-check-label" for="inputAnswer-t-' . $idx . '">True</label>
</div>

<div class="form-check">
  <input class="form-check-input" type="radio" name="answer[' . $row['id'] . ']" id="inputAnswer-f-' . $idx . '" value="0" ' . $disabled . ' ' . ($Answer['answered'] && isset($Answer['data'][$qid]) && $Answer['data'][$qid]['choice'] == '0' ? 'checked' : '') . '>
  <label class="form-check-label" for="inputAnswer-f-' . $idx . '">
    False
  </label>
</div></td>';
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>

        <div class="clearfix">

            <?php
            if ($Answer['answered']) {
                echo '<p class="float-left">Thank you! Your marks are ' . ($Answer['correct'] * 2) . "/" . (count($Question) * 2) . '</p>';
                if ($Answer['correct'] == 0)
                    echo '<a href="?subject=' . $SubjectCode . '&attempt=true" class="btn btn-success float-right">Re-attempt</a>';
                else
                    echo '<a href="subjects.php" class="btn btn-primary float-right">Back</a>';
            } else {
                echo '<button type="submit" class="btn btn-success float-right">Finish Answer</button>';
            }
            ?>
        </div>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
</body>
</html>
