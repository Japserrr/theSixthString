<?php
//
//require_once '../helpers/database.php';
//
//function isAuthorized(): void
//{
//    // check if employee is logged in
//}
//
//function productManagementOverview(): void
//{
//    isAuthorized();
//
//    $products = findProducts();
//    data(null, $products);
//
//    require_once '../views/ProductManagement/product-management.html';
//}
//
//function searchProduct(): void
//{
//    isAuthorized();
//
//    if (empty($_GET['search'])) {
//        header('Location: '.URL_ROOT . '/product-management');
//        return;
//    }
//    $search = $_GET['search'];
//    unset($_GET['search']);
//
//    $products = findProducts($search);
//    data($search, $products);
//
//    require_once '../views/ProductManagement/product-management.html';
//}
//
//function deleteProduct(): void
//{
//    isAuthorized();
//
//    if (empty($_GET['id'])) {
//        header('Location: '.URL_ROOT . '/product-management');
//        return;
//    }
//    $productId = $_GET['id'];
//    unset($_GET['if']);
//
//    try {
//        $conn = getDbConnection();
//        $stmt = $conn->prepare('DELETE FROM product WHERE id = :productId');
//        $stmt->execute(['productId' => $productId]);
//        $conn = null;
//    } catch(Exception $e) {
//        // replace with proper exception.
//        die;
//    }
//
//    header('Location: '.URL_ROOT . '/product-management');
//}
//
//function findProducts(?string $search = null): array
//{
//    $sql = '
//            SELECT
//                p.id,
//                p.product_name AS name,
//                b.brand_name AS brand,
//                p.price,
//                p.quantity,
//                p.description,
//                p.video_url AS videoUrl,
//                p.img_path AS image
//            FROM product AS p
//            LEFT JOIN brand AS b ON b.id  = p.brand_id
//    ';
//    if ($search) {
//        $sql .= '
//            WHERE p.product_name LIKE :search
//            OR b.brand_name LIKE :search
//            OR p.description LIKE :search
//        ';
//        $params = ['search' => "%$search%"];
//    }
//
//    try {
//        $conn = getDbConnection();
//        $stmt = $conn->prepare($sql);
//        $stmt->execute($params ?? null);
//        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $conn = null;
//    } catch(Exception $e) {
//        // replace with proper exception.
//        die;
//    }
//
//    return $products ?: [];
//}
//
//function data(?string $search, array $products): void
//{
//    ?>
<!--    <script type="text/javascript">-->
<!--        const search = --><?php //echo json_encode($search ?? false); ?>//;
//        const products = <?php //echo json_encode($products); ?>//;
//    </script>
//    <?php
//}