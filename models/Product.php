<?php

include_once '../helpers/database.php';

class Product
{
    private $table = 'product';

    public function get($id): array|null
    {
        if (!isset($id) && !is_numeric($_GET['product_id'])) {
            header("Location: " . URL_ROOT . "/404");
        }
        $sql = 'SELECT `product_name`, `description`, `price`, `video_url`, `img_path`, `quantity`, `brand_name` FROM ' . $this->table . ' INNER JOIN `brand` ON `brand`.`id` = `product`.`brand_id` WHERE `product`.`id` LIKE :id LIMIT 1';

        $conn = getDbConnection();

        $sth = $conn->prepare($sql);
        $sth->execute(['id' => $id]);
        $products = $sth->fetchAll() ?? null;

        if (count($products) !== 1) {
            header("Location: " . URL_ROOT . "/404");
            exit;
        }

        $conn = null;
        return $products[0] ?? null;
    }

    public function getCatagories($productId): array|null
    {
        if (!isset($id) && !is_numeric($_GET['product_id'])) {
            header("Location: " . URL_ROOT . "/404");
        }

        $sql = 'SELECT category.category_name FROM product LEFT OUTER JOIN product_category ON product.id = product_category.product_id AND product_category.product_id = :product_id LEFT OUTER JOIN category ON product_category.category_id = category.id WHERE category.category_name is not null';

        $conn = getDbConnection();
        $sth = $conn->prepare($sql);
        $sth->execute(['product_id' => $productId]);
        $categories = $sth->fetchAll();
        $conn = null;

        return $categories ?? null;
    }
}
