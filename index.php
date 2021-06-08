<?php
require_once 'core.php';

if (Users::$Session)
    Helper::Redirect($_SESSION['user_type']);

$Error = array();

if (isset($_POST['id'], $_POST['password'], $_POST['user_type'])) {
    $id = htmlspecialchars(trim($_POST['id']));
    $Pass = htmlspecialchars(trim($_POST['password']));
    $Type = htmlspecialchars(trim($_POST['user_type']));

    if (empty($Type)) {
        $Error[] = "Choose either Student Login or Lecturer Login or Admin Login. Try again!";
    }
    if (empty($id) || empty($Pass)) {
        $Error[] = "Please fill in the blanks.";
    }

    if (empty($Error)) {
        $status = Users::login($id, $Pass, strtolower($Type));

        switch ($status) {
            case 200:
                Helper::Redirect($_SESSION['user_type']);
                break;

            case 403:
                $Error[] = "Choose either Student Login or Lecturer Login or Admin Login. Try again!";
                break;

            case 404:
                $Error[] = "Incorrect Student No or Password. Try again!";
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <title>UTHM - Quiz System</title>
</head>

<body>
<div class="container">
    <div class="row content">
        <div class="col-md-6 mb-3">
            <img src="assets/img/uthm.png" class="img-fluid" alt="uthm">
        </div>
        <div class="col-md-6">
            <?php
            if (!empty($Error)) {
                ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php
                        foreach ($Error as $err) {
                            echo "<li>{$err}</li>";
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
            ?>

            <h3 class="signin-text mb-3">Sign In</h3>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                <div class="form-group">
                    <label for="ID">ID: </label>
                    <input type="id" name="id" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password: </label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="student" name="user_type" required>Student
                        </label>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="lecturer" name="user_type" required>Lecturer
                        </label>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="admin" name="user_type" required>Admin
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Login</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
</body>
</html>
