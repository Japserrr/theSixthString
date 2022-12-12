<body>
    <h1 class="text-center"><?= SITE_NAME ?></h1>
    <p class="text-center"> Welkom <?= $user['first_name'] ?? '' ?> <?= $user['infix'] ?? '' ?> <?= $user['last_name'] ?? ''?></p>
</body>