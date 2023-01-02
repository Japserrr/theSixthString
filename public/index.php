<?php include_once '../config/config.php';
include_once '../helpers/validate.php';
session_start();

check_expire_time(); ?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="<?= URL_MAIN . URL_ROOT?>/public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?= URL_MAIN . URL_ROOT?>//public/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <script src="<?= URL_MAIN . URL_ROOT?>/public/js/jquery/jquery-3.6.2.min.js"></script>
    <script src="<?= URL_MAIN . URL_ROOT?>/public/js/popper/popper.min.js"></script>
    <script src="<?= URL_MAIN . URL_ROOT?>/public/js/bootstrap/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js" defer></script>

    <title><?= SITE_NAME ?></title>
</head>

<body>
    <?php include_once '../views/navbar.phtml'; ?>

    <?php include_once '../controllers/RouteController.php'; ?>

    <script src="./public/js/main.js"></script>
</body>

</html>