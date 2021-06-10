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
    <title>Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        html {
            width: 100%;
            height: 100%;
            background-image: url('/assets/img/uthm.jpg'), linear-gradient(to top, #1a2a6c, #b21f1f, #fdbb2d);
            background-repeat: no-repeat;
            background-position: center;

            min-height: 100%;
            /* background-color: linear-gradient(to right, #0f2027, #203a43, #2c5364);*/
            /*background-image: linear-gradient(217deg, #0f2027, #2193b0 70.71%),*/
            /*linear-gradient(127deg,  #2c5364, #2193b0 70.71%);*/
            /*linear-gradient(336deg, rgba(0,0,255,.8), rgba(0,0,255,0) 70.71%);*/


        }

    </style>
</head>
<body>

<?php include_once 'nav.inc'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>

</body>
</html>
