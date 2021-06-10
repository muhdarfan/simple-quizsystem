<?php
require 'core.php';

if (!isset(Users::$Session) || !isset($_SESSION['change_pass']))
    Helper::Redirect('index.php');

if (isset($_POST['password'], $_POST['confirm_password'])) {
    $Pass = htmlspecialchars(trim($_POST['password']));
    $ConfirmPass = htmlspecialchars(trim($_POST['confirm_password']));

    if (empty($Pass) || empty($ConfirmPass)) {
        Helper::$Error['account'][] = "Please fill in the blanks.";
    } else {
        if (strcmp($Pass, $ConfirmPass) != 0) {
            Helper::$Error['account'][] = "Password doesn't match.";
        } elseif (strcmp($Pass, Users::$Session['id']) == 0) {
            Helper::$Error['account'][] = "Password can't be same with ID.";
        } else {
            $flag = Users::ChangePassword($Pass);

            if (isset($flag['error']))
                Helper::$Error['account'][] = $flag['error'];
            else
                Helper::Redirect(Users::$Session['type']);
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
    <title>UTHM - Change Password</title>
</head>

<body>
<div class="container">
    <div class="row content rounded">
        <div class="col-md-6 offset-md-3">
            <img src="assets/img/uthm.png" class="img-fluid" alt="uthm"/>
            <?php
            if (!empty(Helper::$Error['account'])) {
                ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php
                        foreach (Helper::$Error['account'] as $err) {
                            echo "<li>{$err}</li>";
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
            ?>
            <hr/>

            <h3 class="signin-text mb-3">Update Password</h3>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                <div class="form-group">
                    <label for="ID">ID</label>
                    <input type="text" readonly class="form-control-plaintext" id="ID"
                           value="<?php echo Users::$Session['id']; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password: </label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Confirm Password: </label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
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
