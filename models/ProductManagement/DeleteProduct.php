<?php

require_once 'Common.php';
require_once 'ProductOverview.php';

function deleteProduct(): void
{
    isAuthorized();


    if (empty($_GET['id'])) {
        header('Location: '.URL_ROOT . '/product-overview');
        return;
    }

    $productId = $_GET['id'];
    $productName = $_GET['name'];

    unset($_GET['id'], $_GET['name']);

    try {
        $conn = getDbConnection();

        $stmt = $conn->prepare('DELETE FROM product_category WHERE product_id = :productId');
        $stmt->execute(['productId' => $productId]);

        $stmt = $conn->prepare('DELETE FROM product WHERE id = :productId');
        $stmt->execute(['productId' => $productId]);

        $conn = null;

        $notification = [
            'type' => 'success',
            'message' => "<strong>$productName</strong> is succesvol verwijderd.",
        ];
    } catch(Exception $e) {
        $notification = [
            'type' => 'danger',
            'message' => 'Er is een fout opgetreden. Probeer het opnieuw.',
        ];
    }

    productOverview($notification);
}