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

if (isset($_POST['question'], $_POST['answer_a'], $_POST['answer_b'], $_POST['answer_c'], $_POST['answer_d'], $_POST['answer_correct'])) {
    $Question = htmlspecialchars(trim($_POST['question']));
    $Answer = array(
        'a' => htmlspecialchars(trim($_POST['answer_a'])),
        'b' => htmlspecialchars(trim($_POST['answer_b'])),
        'c' => htmlspecialchars(trim($_POST['answer_c'])),
        'd' => htmlspecialchars(trim($_POST['answer_d'])),
        'correct' => $_POST['answer_correct']
    );

    if (isset($_GET['edit'])) {
        $flag = Quiz::UpdateMCQ(intval($_GET['edit']), $Question, $Answer);

    } else {
        $flag = Quiz::AddMCQ($SubjectCode, $Question, $Answer);

    }
    if (isset($flag['error'])) {
        Helper::$Error['mcq'][] = $flag['error'];
        print_r($flag['error']);
    } else {
        Helper::Redirect($_SERVER['PHP_SELF']. '?id='.$SubjectCode);
    }
}

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);

    $Quiz = Quiz::RetrieveMCQQuestion($SubjectCode, $id);

    if (empty($Quiz))
        Helper::Redirect($_SERVER['PHP_SELF'] . '?id=' . $SubjectCode);
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $flag = Quiz::DeleteMCQ($id);
    if (isset($flag['error']))
        Helper::$Error['quiz'][] = $flag['error'];
    else
        Helper::Redirect($_SERVER['PHP_SELF']. '?id='.$SubjectCode);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UTHM - MCQ Quiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
<?php include_once 'nav.inc'; ?>

<div class="container p-3">
    <div class="page-header border-bottom mb-3 text-center">
        <h2>MCQ Quiz</h2>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
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
                    <label for="inputQuestion" class="col-sm-4 col-form-label">Question</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" id="inputQuestion" name="question" placeholder="Question here..."
                                  rows="3"
                                  style="resize: none;"><?php if (isset($_GET['edit'])) echo $Quiz['question']; ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputAnswerA" class="col-sm-4 col-form-label">Answer A</label>
                    <div class="col-sm-8">
                        <input type="text" name="answer_a" class="form-control" id="inputAnswerA"
                               value="<?php if (isset($_GET['edit'])) echo $Quiz['answer_a']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputAnswerB" class="col-sm-4 col-form-label">Answer B</label>
                    <div class="col-sm-8">
                        <input type="text" name="answer_b" class="form-control" id="inputAnswerB"
                               value="<?php if (isset($_GET['edit'])) echo $Quiz['answer_b']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputAnswerC" class="col-sm-4 col-form-label">Answer C</label>
                    <div class="col-sm-8">
                        <input type="text" name="answer_c" class="form-control" id="inputAnswerC"
                               value="<?php if (isset($_GET['edit'])) echo $Quiz['answer_c']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputAnswerD" class="col-sm-4 col-form-label">Answer D</label>
                    <div class="col-sm-8">
                        <input type="text" name="answer_d" class="form-control" id="inputAnswerD"
                               value="<?php if (isset($_GET['edit'])) echo $Quiz['answer_d']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputCorrectAnswer" class="col-sm-4 col-form-label">Correct Answer</label>
                    <div class="col-sm-8">
                        <select name="answer_correct" class="form-control" id="inputCorrectAnswer">
                            <option value='' selected disabled>Please choose a correct answer</option>
                            <option value='a' <?php if (isset($_GET['edit']) && $Quiz['correct_answer'] == 'a') echo 'selected'; ?>>
                                A
                            </option>
                            <option value='b' <?php if (isset($_GET['edit']) && $Quiz['correct_answer'] == 'b') echo 'selected'; ?>>
                                B
                            </option>
                            <option value='c' <?php if (isset($_GET['edit']) && $Quiz['correct_answer'] == 'c') echo 'selected'; ?>>
                                C
                            </option>
                            <option value='d' <?php if (isset($_GET['edit']) && $Quiz['correct_answer'] == 'd') echo 'selected'; ?>>
                                D
                            </option>
                        </select>
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

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th style="width: 3%">No</th>
            <th style="width: 30%;">Question</th>
            <th>Answer A</th>
            <th>Answer B</th>
            <th>Answer C</th>
            <th>Answer D</th>
            <th>Correct Answer</th>
            <th>Modified By</th>
            <th>Modified On</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (Quiz::RetrieveMCQQuestion($SubjectCode) as $idx => $row) {
            $idx++;
            $row['correct_answer'] = strtoupper($row['correct_answer']);
            echo "<tr>";
            echo "<td>{$idx}</td>";
            echo "<td>{$row['question']}</td>";
            echo "<td>{$row['answer_a']}</td>";
            echo "<td>{$row['answer_b']}</td>";
            echo "<td>{$row['answer_c']}</td>";
            echo "<td>{$row['answer_d']}</td>";
            echo "<td>{$row['correct_answer']}</td>";
            echo "<td>{$row['updated_by']}</td>";
            echo "<td>{$row['updated_at']}</td>";
            echo "<td class='text-center'><a href='?id={$SubjectCode}&edit={$row['id']}' class='btn btn-info'>Update</a> <a href='?id={$SubjectCode}&delete={$row['id']}' onclick='return confirm(\"Are you sure want to delete this record?\")' class='btn btn-danger'>Delete</a></td>";
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
