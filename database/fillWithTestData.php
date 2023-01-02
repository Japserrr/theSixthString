<?php

include_once '../helpers/database.php';

$amounts = [
    'user'    =>  50,
    'brand'   =>  20,
    'product' => 250,
];

$categoryNames = [
    'Elektrische gitaar',
    'Elektrische gitaren',
    'Semi akoestische gitaar',
    'Elektrische gitaar set',
    'Kindergitaar',
    'Akoestische gitaar',
    'Klassieke gitaar',
    'Western gitaar',
    'Resonator gitaar',
    'Akoestische gitaar set',
    'Gitaar startersets',
    'Elektrisch akoestische gitaar',
    'Elektrisch akoestische klassieke gitaar',
    'Elektrisch akoestische westerngitaar',
    'Linkshandige gitaar',
    'Reisgitaar',
    'Tweedehands gitaar',
    'Gitaarversterker',
    'Gitaarversterker combo',
    'Gitaarversterker top',
    'Gitaar speakerkast',
    'Akoestische gitaarversterker',
    'Gitaareffecten',
    'Stompbox',
    'Multi-effect pedaal',
    'Volumepedaal & expressiepedaal',
    'Gitaar-voorversterkers',
    'Gitaar accessoires',
    'Gitaarband',
    'Gitaar statief',
    'Capo',
    'Stemapparaat',
    'Gitaar onderdelen & gereedschap',
    'Gitaar bouwpakket',
    'Gitaar gereedschap',
    'Gitaar Afstel- en Onderhoudservice',
    'Straplocks & strapbuttons',
    'Plectrums',
    'Gitaarsnaren',
    'Elektrische gitaarsnaren',
    'Western gitaarsnaren',
    'Klassieke gitaarsnaren',
    'Resonator snaren'
];

function generateUsers(int $amount): bool
{
    $firstNameArray = array(
        'Johnathon',
        'Anthony',
        'Erasmo',
        'Raleigh',
        'Nancie',
        'Tama',
        'Camellia',
        'Augustine',
        'Christeen',
        'Luz',
        'Diego',
        'Lyndia',
        'Thomas',
        'Georgianna',
        'Leigha',
        'Alejandro',
        'Marquis',
        'Joan',
        'Stephania',
        'Elroy',
        'Zonia',
        'Buffy',
        'Sharie',
        'Blythe',
        'Gaylene',
        'Elida',
        'Randy',
        'Margarete',
        'Margarett',
        'Dion',
        'Tomi',
        'Arden',
        'Clora',
        'Laine',
        'Becki',
        'Margherita',
        'Bong',
        'Jeanice',
        'Qiana',
        'Lawanda',
        'Rebecka',
        'Maribel',
        'Tami',
        'Yuri',
        'Michele',
        'Rubi',
        'Larisa',
        'Lloyd',
        'Tyisha',
        'Samatha',
    );
    $infixArray = array(
        '',
        'van',
        'de',
        'van der'
    );
    $lastNameArray = array(
        'Mischke',
        'Serna',
        'Pingree',
        'Mcnaught',
        'Pepper',
        'Schildgen',
        'Mongold',
        'Wrona',
        'Geddes',
        'Lanz',
        'Fetzer',
        'Schroeder',
        'Block',
        'Mayoral',
        'Fleishman',
        'Roberie',
        'Latson',
        'Lupo',
        'Motsinger',
        'Drews',
        'Coby',
        'Redner',
        'Culton',
        'Howe',
        'Stoval',
        'Michaud',
        'Mote',
        'Menjivar',
        'Wiers',
        'Paris',
        'Grisby',
        'Noren',
        'Damron',
        'Kazmierczak',
        'Haslett',
        'Guillemette',
        'Buresh',
        'Center',
        'Kucera',
        'Catt',
        'Badon',
        'Grumbles',
        'Antes',
        'Byron',
        'Volkman',
        'Klemp',
        'Pekar',
        'Pecora',
        'Schewe',
        'Ramage',
    );

    // Example Database
    $conn = getDbConnection();

    for ($i = 0; $i < $amount; $i++) {
        $name = $firstNameArray[rand(0, count($firstNameArray) - 1)];
        $infix = $infixArray[rand(0, count($infixArray) - 1)];
        $surname = $lastNameArray[rand(0, count($lastNameArray) - 1)];
        $phone = intval(316 . rand(10000, 99999));

        $sqlAuth = "INSERT INTO auth (`password`, `email`, `active`, `created_at`) VALUES (?, ?, ?, NOW())";
        $conn->prepare($sqlAuth)->execute([
            'geheim',
            $name . $infix . $surname . '@email.com',
            true
        ]);

        $authId = $conn->lastInsertId();
        $rand = rand(0,1);
        $sql = "INSERT INTO user (auth_id, first_name, infix, last_name, phone_number, employee) VALUES (?, ?, ?, ?, ?, $rand)";
        $conn->prepare($sql)->execute([$authId, $name, $infix, $surname, $phone]);
    }

    $conn = null;
    return true;
}

function generateCategories($categoryNames): bool
{
    $conn = getDbConnection();

    foreach ($categoryNames as $category) {
        $sql = "INSERT INTO category (category_name) VALUES (?)";
        $conn->prepare($sql)->execute([$category]);
    }
    $conn = null;

    return true;
}

function generateBrands($amount): bool
{
    $conn = getDbConnection();

    for ($i = 1; $i <= $amount; $i++) {
        $sql = "INSERT INTO brand (`brand_name`) VALUES (?)";
        $brandName = 'Brand #' . $i;
        $conn->prepare($sql)->execute([$brandName]);
    }
    $conn = null;

    return true;
}

function generateProduct($amountProduct, $amountBrand): bool
{
    $conn = getDbConnection();

    for ($i = 1; $i <= $amountProduct; $i++) {
        $sql = "INSERT INTO product (`product_name`, `brand_id`, `price`, `quantity`, `description`, `video_url`, `img_path`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $productName = 'Gitaar #' . $i;

        $conn->prepare($sql)->execute([
            $productName,
            rand(1, $amountBrand),
            rand(0, 100000) / 100,
            rand(0, 20),
            'Beschrijving van het product ' . $productName,
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            './public/img/Gitaar01.jpg'
        ]);
    }
    $conn = null;

    return true;
}

function generateProductCategory($amountProduct, $amountCatagories): bool
{
    $conn = getDbConnection();

    for ($i = 1; $i <= $amountProduct; $i++) {
        $sql = "INSERT INTO product_category (`product_id`, `category_id`) VALUES (?, ?)";
        $conn->prepare($sql)->execute([
            $i,
            rand(1, $amountCatagories),
        ]);
    }
    $conn = null;

    return true;
}

// Load data
generateUsers($amounts['user']);
generateCategories($categoryNames);
generateBrands($amounts['brand']);
generateProduct($amounts['product'], $amounts['brand']);
generateProductCategory($amounts['product'], count($categoryNames));
