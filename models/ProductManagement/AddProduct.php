<?php

require_once 'Common.php';
require_once 'ProductOverview.php';

function addProduct(): void
{
    isAuthorized();

    $sanitizedData = sanitizePostData();
    $productName = $sanitizedData['productName'];

    try {
        $conn = getDbConnection();

        $stmt = $conn->prepare('SELECT * FROM product WHERE product_name = :productName');
        $stmt->execute(['productName' => $productName]);

        if (!empty($stmt->fetchAll())) {
            $notification = [
                'type' => 'danger',
                'message' => "Product met de naam <strong>$productName</strong> bestaat al.",
            ];
        } else {
            $imagePath = moveImage($_FILES['image'], $productName);

            $stmt = $conn->prepare('INSERT INTO product VALUES (null, :productName, :brandId, :price, :quantity, :description, :videoUrl, :imagePath)');
            $stmt->execute([
                'productName' => $productName,
                'brandId' => $sanitizedData['brandId'],
                'price' => $sanitizedData['price'],
                'quantity' => $sanitizedData['quantity'],
                'description' => $sanitizedData['description'],
                'videoUrl' => $sanitizedData['videoUrl'],
                'imagePath' => $imagePath,
            ]);

            $productId = $conn->lastInsertId();
            foreach ($sanitizedData['categoryIds'] as $categoryId) {
                $stmt = $conn->prepare('INSERT INTO product_category VALUES (:productId, :categoryId)');
                $stmt->execute([
                    'productId' => $productId,
                    'categoryId' => $categoryId,
                ]);
            }

            $notification = [
                'type' => 'success',
                'message' => "<strong>$productName</strong> is succesvol toegevoegd.",
            ];
        }

        $conn = null;
    } catch (Exception $e) {
        $notification = [
            'type' => 'danger',
            'message' => 'Er is een fout opgetreden. Probeer het opnieuw.',
        ];
    }

    productOverview($notification);
}

function moveImage(array $image, string $productName): string
{
    try {
        $imageName = str_replace(' ', '-', $productName);
        $dateTimeString = str_replace(' ', '_', date('dmyHis'));
        $imageFileInfo = pathinfo($image['name']);

        $newImagePath = "./public/img/products/$imageName" . "_$dateTimeString." . $imageFileInfo['extension'];
        move_uploaded_file($image['tmp_name'], ".$newImagePath");
    } catch (Exception $e) {
        $notification = [
            'type' => 'danger',
            'message' => 'Er is een fout opgetreden. Probeer het opnieuw.',
        ];
    }

    return $newImagePath;
}