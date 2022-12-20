


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">


</head>
<body>
<div class="wrapper">
    <img src="#" alt="">
    <div class="content">
        <header>Cookies Consent</header>
        <p>Onze website, The Sixth Guitar, maakt gebruik van cookies en daarmee vergelijkbare technieken. The Sixth Guitar gebruikt functionele cookies om het gedrag van websitebezoekers na te gaan en de website aan de hand van deze gegevens te verbeteren. Daarnaast plaatsen derden marketing cookies om gepersonaliseerde advertenties te tonen. Met het plaatsen van marketing cookies worden persoonsgegevens verwerkt. Je geeft toestemming voor deze verwerking wanneer je hieronder op Doorgaan naar de website klikt. Lees voor meer informatie onze privacy- en cookieverklaring.</p>
        <div class="buttons">
            <button class="item">Doorgaan naar de website</button>
            <a href="../cookies/privacy.php" class="item">Privacy- en cookieverklaring.</a>
        </div>
    </div>
</div>

<script>
    const cookieBox = document.querySelector(".wrapper"),
        acceptBtn = cookieBox.querySelector("button");

    acceptBtn.onclick = ()=>{
        //setting cookie for 1 month, after one month it'll be expired automatically
        document.cookie = "CookieBy=cookies; max-age="+60*60*24*30;
        if(document.cookie){ //if cookie is set
            cookieBox.classList.add("hide"); //hide cookie box
        }else{ //if cookie not set then alert an error
            alert("Cookie can't be set! Please unblock this site from the cookie setting of your browser.");
        }
    }
    let checkCookie = document.cookie.indexOf("CookieBy=cookies"); //checking our cookie
    //if cookie is set then hide the cookie box else show it
    checkCookie != -1 ? cookieBox.classList.add("hide") : cookieBox.classList.remove("hide");
</script>

</body>
</html>