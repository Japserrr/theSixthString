<?php include_once '../config/config.php'; ?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="./public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="./public/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <script src="./public/js/jquery/jquery-3.6.2.min.js"></script>
    <script src="./public/js/popper/popper.min.js"></script>
    <script src="./public/js/bootstrap/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />

    <title><?= SITE_NAME ?></title>
</head>

<body>
    <?php include_once '../views/navbar.phtml'; ?>

    <?php include_once '../controllers/RouteController.php'; ?>

    <script src="./public/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
    <script>
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "#383b75"
                },
                "button": {
                    "background": "#f1d600"
                }
            },
            "showLink": false,
            "theme": "classic",
            "type": "opt-out",
            "content": {
                "message": "<p>Onze website, the Sixth String, maakt gebruik van cookies en daarmee vergelijkbare technieken. the Sixth String gebruikt functionele cookies om het gedrag van websitebezoekers na te gaan en de website aan de hand van deze gegevens te verbeteren. Daarnaast plaatsen derden marketing cookies om gepersonaliseerde advertenties te tonen." +
                    " Met het plaatsen van marketing cookies worden persoonsgegevens verwerkt. Je geeft toestemming voor deze verwerking wanneer je hieronder een vinkje plaatst. Lees voor meer informatie onze <a href='./privacy' >privacy-en cookieverklaring</a></p> "
            }
        });
    </script>
</body>

</html>