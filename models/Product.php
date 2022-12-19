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
        $sql = 'SELECT `product_name`, `description`, `price`, `video_url` FROM ' . $this->table . ' WHERE id LIKE :id LIMIT 1';

        $conn = getDbConnection();

        $sth = $conn->prepare($sql);
        $sth->execute(['id' => $id]);
        $products = $sth->fetchAll() ?? null;

        if (count($products) !== 1) {
            header("Location: " . URL_ROOT . "/404");
        }
        
        $conn = null;
        return $products[0] ?? null;
    }
}
