<?php

require_once 'Common.php';
require_once 'ProductOverview.php';

function addCategory(): void
{
    isAuthorized();

    $sanitizedData = sanitizePostData();
    $categoryName = $sanitizedData['categoryName'];

    try {
        $conn = getDbConnection();

        $stmt = $conn->prepare('SELECT * FROM category WHERE category_name = :categoryName');
        $stmt->execute(['categoryName' => $categoryName]);

        if (!empty($stmt->fetchAll())) {
            $notification = [
                'type' => 'danger',
                'message' => "<strong>$categoryName</strong> bestaat al.",
            ];
        } else {
            $stmt = $conn->prepare('INSERT INTO category VALUES (null, :categoryName)');
            $stmt->execute(['categoryName' => $categoryName]);
            $notification = [
                'type' => 'success',
                'message' => "<strong>$categoryName</strong> is succesvol toegevoegd.",
            ];
        }

        $conn = null;
    } catch(Exception $e) {
        $notification = [
            'type' => 'danger',
            'message' => 'Er is een fout opgetreden. Probeer het opnieuw.',
        ];
    }

    productOverview($notification);
}