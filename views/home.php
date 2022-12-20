<body>
    <h1 class="text-center"><?= SITE_NAME ?></h1>
    <p class="text-center"> Welkom <?= $_SESSION['firstname'] ?> <?= $_SESSION['infix'] ?? ""   ?> <?= $_SESSION['lastname'] ?></p>


    <?php
    //if session is null or logged_in is false redirect to login page
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
        header('Location: ' . URL_ROOT . '/login');
    }

    ?>
</body>