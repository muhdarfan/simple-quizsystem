<?php
require 'core.php';
header("refresh: 5; url=/");
Users::logout();
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

            <h3 class="signin-text mb-3">Logged Out</h3>
            <div class="text-center">
                <p>You have been successfully logged out! You will be redirected to landing page in 5 seconds.<br/>
                    Click the button below if you've not redirected.</p>
                <a href="/" class="btn btn-success">Redirect</a>
            </div>

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
