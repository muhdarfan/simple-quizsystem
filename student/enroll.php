<?php
require '../core.php';

if (!Users::HasAccess('student'))
    Helper::Redirect('/');

if (isset($_GET['register'])) {
    $code = htmlspecialchars(trim($_GET['register']));

    $flag = Subject::Retrieve($code);

    if (isset($flag['error'])) {
        Helper::$Error['enroll'][] = $flag['error'];
    } elseif (empty($flag)) {
        Helper::$Error['enroll'][] = "Subject doesn't found.";
    } else {
        $reg_flag = Subject::AssignStudent(Users::$Session['id'], $code);

        if (isset($reg_flag['error'])) {
            if (strpos($reg_flag['error'], 'Duplicate'))
                Helper::$Error['enroll'][] = 'You already registered the subject.';
            else
                Helper::$Error['enroll'][] = $reg_flag['error'];
        } else {
            Helper::Redirect($_SERVER['PHP_SELF'] . "?success={$code}");
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
    <title>UTHM - Register Subject</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
<?php include_once 'nav.inc'; ?>

<div class="container">
    <div class="container p-3">
        <div class="page-header border-bottom mb-3">
            <h2 class="text-center">Register Subject</h2>
        </div>

        <?php
        if (isset($_GET['success']))
            echo "<div class='alert alert-success' role='alert'>Successfully registered {$_GET['success']} subject!</div>";

        if (isset(Helper::$Error['enroll'])) {
            echo "<div class='alert alert-danger' role='alert'>";
            foreach (Helper::$Error['enroll'] as $err)
                print_r($err);
            echo "</div>";
        }
        ?>

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>No</th>
                <th>Lecturer</th>
                <th>Subject</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach (Subject::RetrieveByLecturer() as $idx => $row) {
                $idx++;
                echo "<tr>";
                echo "<td>{$idx}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['subject_name']}</td>";
                echo "<td><a href='?register={$row['subject_code']}' class='btn btn-success'>Register</a></td>";
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
