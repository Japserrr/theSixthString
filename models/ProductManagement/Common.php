<?php

function isAuthorized(): void
{
    // check if employee is logged in
}

function sanitizePostData(): array
{
    if (empty($_POST)) {
        // Add proper error handling.
        die;
    }

    $sanitizedData = [];
    foreach ($_POST as $key => $value) {
        // Add proper filter.
        $sanitizedValue = $value;
        $sanitizedData += [$key => $sanitizedValue];
    }

    unset($_POST);

    return $sanitizedData;
}

function findProducts(?string $search = null): array
{
    $sql = '
        SELECT 
            p.id AS productId, 
            p.product_name AS productName,
            b.id AS brandId,
            p.price,
            p.quantity,
            p.description,
            p.video_url AS videoUrl,
            p.img_path AS imagePath
        FROM product AS p 
        LEFT JOIN brand AS b ON b.id  = p.brand_id
    ';

    if ($search) {
        $sql .= '
            WHERE p.product_name LIKE :search
            OR b.brand_name LIKE :search
            OR p.description LIKE :search
        ';
        $params = ['search' => "%$search%"];
    }

    try {
        $conn = getDbConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params ?? null);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
    } catch(Exception $e) {
        // replace with proper exception.
        die;
    }

    return $products;
}

function findBrands(): array
{
    try {
        $conn = getDbConnection();
        $brands = $conn->query('SELECT id, brand_name AS brandName FROM brand')->fetchAll() ?? [];
        $conn = null;
    } catch(Exception $e) {
        // replace with proper exception.
        die;
    }

    return $brands;
}

function findCategories(): array {
    try {
        $conn = getDbConnection();
        $categories = $conn->query('SELECT id, category_name AS categoryName FROM category')->fetchAll() ?? [];
        $conn = null;
    } catch(Exception $e) {
        // replace with proper exception.
        die;
    }

    return $categories;
}

// Emit php data to front end.
function printJsonData(array $data): void
{
    $brands = findBrands();
    $categories = findCategories();

    $dataString = '';
    foreach ($data as $key => $value) {
        $jsonValue = json_encode($value);
        $dataString .= "const $key = $jsonValue;";
    }

    ?>
    <script type="text/javascript">
        const brands = <?= json_encode($brands)?>;
        const categories = <?= json_encode($categories)?>;

        <?= $dataString ?>
    </script>
    <?php
}