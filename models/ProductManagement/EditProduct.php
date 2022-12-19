<?php

require_once 'Common.php';
require_once 'ProductOverview.php';

function editProduct(): void
{
    isAuthorized();

//    if (empty($_POST)) {
////        header('Location: '.URL_ROOT . '/product-overview');
//        // niet gelukt
//        return;
//    }

    if (empty($_POST['productName'])
        || empty($_POST['brandId']))
    {
//        header('Location: '.URL_ROOT . '/product-overview');
        // niet gelukt
        return;
    }

    if (!empty($_FILES['image']['name'])) {
        $imagePath = replaceImage($_FILES['image'], $_POST['productId'], $_POST['productName']);
    }

    try {
        $conn = getDbConnection();
        $stmt = $conn->prepare('
            UPDATE product
            SET product_name = :productName,
                brand_id = :brandId,
                price = :price,
                quantity = :quantity,
                description = :description,
                video_url = :videoUrl
            WHERE id = :id');
        $stmt->execute([
            'productName' => $_POST['productName'],
            'brandId' => $_POST['brandId'],
            'price' => $_POST['price'],
            'quantity' => $_POST['quantity'],
            'description' => $_POST['description'],
            'videoUrl' => $_POST['videoUrl'],
            'id' => $_POST['productId'],
        ]);
        if (!empty($_FILES['image']['name'])) {
            $stmt = $conn->prepare('
            UPDATE product
            SET img_path = :imagePath
            WHERE id = :id');
            $stmt->execute([
                'imagePath' => $imagePath,
                'id' => $_POST['productId'],
            ]);
        }
        $conn = null;
    } catch(Exception $e) {
        // replace with proper exception.
        $notification = [
            'type' => 'danger',
            'message' => 'Er is een fout opgetreden. Probeer het opnieuw.',
        ];
    }

    $notification = [
        'type' => 'success',
        'message' => "<strong>". $_POST['productName'] . "</strong> is succesvol aangepast.",
    ];

    productOverview($notification);
}

function replaceImage(array $image, int $productId, string $productName): string
{
    // Add new image.
    try {
        $imageName = str_replace(' ', '', $productName);
        $dateTimeString = str_replace(' ', '_', date('dmyHis'));
        $imageFileInfo = pathinfo($image['name']);

        // Max length is product name (max 255) + date time string (13) + file extension (max 4) = 272.
        $newImagePath = "./public/img/products/$imageName" . "_$dateTimeString." . $imageFileInfo['extension'];
        move_uploaded_file($image['tmp_name'], ".$newImagePath");
    } catch (Exception $e) {
        // replace with proper exception.
        die;
    }

    // Delete old image.
    try {
        $conn = getDbConnection();
        $result = $conn->prepare('SELECT img_path AS imagePath FROM product WHERE id = :id');
        $result->execute(['id' => $productId]);
        $oldImagePath = $result->fetch();
        $conn = null;

        unlink('.'.$oldImagePath['imagePath']);
    } catch(Exception $e) {
        // replace with proper exception.
        die;
    }

    return $newImagePath;
}