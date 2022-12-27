<?php include_once '../config/config.php'; ?>
<?php include_once '../cookies/cookie.php' ?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="./public/css/style.css" rel="stylesheet">
    <script src="./public/js/jquery/jquery-3.6.2.min.js"></script>
    <script src="./public/js/popper/popper.min.js"></script>
    <script src="./public/js/bootstrap/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">


    <title><?= SITE_NAME ?></title>
</head>
<body>

<header class="sticky-top px-4 bg-white w-100 d-flex align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
    <a href="./home" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
        <h1><?= SITE_NAME ?></h1>
    </a>

    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="./home" class="nav-link px-2 link-secondary">Home</a></li>
        <li><a href="./product-overview" class="nav-link px-2 link-dark">Product management</a></li>
        <!--        <li><a href="#" class="nav-link px-2 link-dark">test3</a></li>-->
        <!--        <li><a href="#" class="nav-link px-2 link-dark">test4</a></li>-->
        <!--        <li><a href="#" class="nav-link px-2 link-dark">test5</a></li>-->
    </ul>

    <div class="col-md-3 text-end">
        <?php require_once '../helpers/validate.php';
        if (isLoggedIn()) {
           echo "<a href='./login' type='button' class='btn btn-outline-success me-2'>uitlogge</a>";
        } else {
        echo "<a href='./login' type='button' class='btn btn-outline-success me-2'>Inloggen</a>";
        echo "<a href='./register' type='button' class='btn btn-success me-2'>Registreren</a>";
} ?>
    </div>
</header>
<?php include_once '../controllers/RouteController.php'; ?>
</body>
<script src="./public/js/main.js"></script>

</html>