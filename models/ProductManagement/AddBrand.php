<?php

require_once 'Common.php';
require_once 'ProductOverview.php';

function addBrand(): void
{
    isAuthorized();

    $sanitizedData = sanitizePostData();
    $brandName = $sanitizedData['brandName'];

    try {
        $conn = getDbConnection();

        $stmt = $conn->prepare('SELECT * FROM brand WHERE brand_name = :brandName');
        $stmt->execute(['brandName' => $brandName]);

        if (!empty($stmt->fetchAll())) {
            $notification = [
                'type' => 'danger',
                'message' => "<strong>$brandName</strong> bestaat al.",
            ];
        } else {
            $stmt = $conn->prepare('INSERT INTO brand VALUES (null, :brandName)');
            $stmt->execute(['brandName' => $brandName]);
            $notification = [
                'type' => 'success',
                'message' => "<strong>$brandName</strong> is succesvol toegevoegd.",
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
