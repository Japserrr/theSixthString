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

        $sql = 'SELECT `product`.`id`, `product_name`, `description`, `price`, `video_url`, `img_path`, `quantity`, `brand_name` FROM ' . $this->table . ' INNER JOIN `brand` ON `brand`.`id` = `product`.`brand_id` WHERE `product`.`id` LIKE :id LIMIT 1';


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



    /**
     * @param string|null $searchString
     * @return array
     */
    public function findAll(?string $searchString = null): array
    {
        try {
            $sql = '
                SELECT 
                    p.id AS id, 
                    p.product_name AS name,
                    p.brand_id AS brandId,
                    p.price,
                    p.quantity,
                    p.description,
                    p.video_url AS videoUrl,
                    p.img_path AS imagePath
                FROM product AS p
            ';

            if ($searchString) {
                $params = ['search' => "%$searchString%"];
                $sql .= '
                    INNER JOIN brand AS b ON b.id = p.brand_id
                    LEFT JOIN product_category AS pc ON pc.product_id = p.id
                    INNER JOIN category AS c ON c.id = pc.category_id
                    WHERE p.product_name LIKE :search
                        OR b.brand_name LIKE :search
                        OR p.description LIKE :search
                        OR c.category_name LIKE :search
                ';
            }

            $sql .= 'GROUP BY p.id';

            $conn = getDbConnection();

            $stmt = $conn->prepare($sql);
            $stmt->execute($params ?? null);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($products)) {
                foreach ($products as $key => $product) {
                    $sql = '
                        SELECT pc.category_id AS categoryId
                        FROM product_category AS pc
                        WHERE pc.product_id = :productId
                    ';

                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['productId' => $product['id']]);
                    $categoryIds = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $products[$key]['categoryIds'] = [];
                    if (!empty($categoryIds)) {
                        foreach ($categoryIds as $categoryId) {
                            $products[$key]['categoryIds'][] = $categoryId['categoryId'];
                        }
                    }
                }
            }
        } catch (Exception) {
            $conn = null;
            return [];
        }

        $conn = null;
        return $products;
    }

    /**
     * @param array{
     *     name: string,
     *     brandId: int,
     *     quantity: int,
     *     price: float,
     *     categoryIds: int[],
     *     description: string,
     *     videoUrl: string
     *     } $product
     * @param array $image
     * @return bool
     */
    public function add(array $product, array $image): bool
    {
        try {
            $conn = getDbConnection();

            if (!empty($image['name']['image'])) {
                $imagePath = $this->saveImageFile($image, $product['name']);
            }

            $stmt = $conn->prepare('
                INSERT INTO product 
                VALUES (null, :name, :brandId, :price, :quantity, :description, :videoUrl, :imagePath)
            ');
            $stmt->execute([
                'name' => $product['name'],
                'brandId' => $product['brandId'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'description' => $product['description'],
                'videoUrl' => $product['videoUrl'],
                'imagePath' => $imagePath ?? null,
            ]);
            $productId = $conn->lastInsertId();

            if (!empty($product['categoryIds'])) {
                foreach ($product['categoryIds'] as $categoryId) {
                    $stmt = $conn->prepare('
                        INSERT INTO product_category 
                        VALUES (:productId, :categoryId)
                    ');
                    $stmt->execute([
                        'productId' => $productId,
                        'categoryId' => $categoryId,
                    ]);
                }
            }
        } catch (Exception) {
            $conn = null;
            return false;
        }

        $conn = null;
        return true;
    }

    /**
     * @param array{
     *     id: int,
     *     name: string,
     *     brandId: int,
     *     quantity: int,
     *     price: float,
     *     categoryIds: int[],
     *     description: string,
     *     videoUrl: string,
     *     imagePath: ?string
     *     } $product
     * @param array $image
     * @return bool
     */
    public function update(array $product, array $image): bool
    {
        try {
            $conn = getDbConnection();

            if (empty($product['imagePath']) || !empty($image['name']['image'])) {
                $stmt = $conn->prepare('SELECT img_path AS imagePath FROM product WHERE id = :id AND img_path IS NOT NULL');
                $stmt->execute(['id' => $product['id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($result['imagePath'])) {
                    if (!$this->removeImageFile($result['imagePath'])) {
                        return false;
                    }
                }
            }

            $imagePath = null;
            if (!empty($product['imagePath'])) {
                $imagePath = $product['imagePath'];
            }
            if (!empty($image['name']['image'])) {
                $imagePath = $this->saveImageFile($image, $product['name']);
            }

            $stmt = $conn->prepare('
                UPDATE product 
                SET product_name = :name, 
                    brand_id = :brandId, 
                    price = :price, 
                    quantity = :quantity, 
                    description = :description, 
                    video_url = :videoUrl, 
                    img_path = :imagePath
                WHERE id = :id
            ');
            $stmt->execute([
                'id' => $product['id'],
                'name' => $product['name'],
                'brandId' => $product['brandId'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'description' => $product['description'],
                'videoUrl' => $product['videoUrl'],
                'imagePath' => $imagePath,
            ]);

            $stmt = $conn->prepare('
                    DELETE FROM product_category 
                    WHERE product_id = :productId
                ');
            $stmt->execute([
                'productId' => $product['id'],
            ]);

            if (!empty($product['categoryIds'])) {
                foreach ($product['categoryIds'] as $categoryId) {
                    $stmt = $conn->prepare('
                        INSERT INTO product_category 
                        VALUES (:productId, :categoryId)
                    ');
                    $stmt->execute([
                        'productId' => $product['id'],
                        'categoryId' => $categoryId,
                    ]);
                }
            }
        } catch(Exception) {
            $conn = null;
            return false;
        }

        $conn = null;
        return true;
    }

    /**
     * @param int $productId
     * @return bool
     */
    public function delete(int $productId): bool
    {
        try {
            $conn = getDbConnection();

            $stmt = $conn->prepare('DELETE FROM product_category WHERE product_id = :productId');
            $stmt->execute(['productId' => $productId]);

            $stmt = $conn->prepare('DELETE FROM product WHERE id = :productId');
            $stmt->execute(['productId' => $productId]);
        } catch (Exception) {
            $conn = null;
            return false;
        }

        $conn = null;
        return true;
    }

    /** ======== Helpers ======== */

    /**
     * @param int $id
     * @return string|null
     */
    public function findById(int $id): ?string
    {
        try {
            $conn = getDbConnection();
            $stmt = $conn->prepare('SELECT product_name AS name FROM product WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception) {
            $conn = null;
            return null;
        }

        $conn = null;
        return $result['name'] ?: null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function nameExists(string $name): bool
    {
        try {
            $conn = getDbConnection();
            $stmt = $conn->prepare('SELECT * FROM product WHERE product_name = :name');
            $stmt->execute(['name' => $name]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception) {
            $conn = null;
            return false;
        }

        $conn = null;
        return !empty($result);
    }

    /**
     * @param array $image
     * @param string $productName
     * @return string|null
     */
    private function saveImageFile(array $image, string $productName): ?string
    {
        try {
            $dateTimeString = str_replace(' ', '_', date('dmyHis'));
            $imageFileInfo = pathinfo($image['name']['image']);

            $newImagePath = "./public/img/products/product_$dateTimeString." . $imageFileInfo['extension'];
            move_uploaded_file($image['tmp_name']['image'], ".$newImagePath");
        } catch (Exception) {
            return null;
        }

        return $newImagePath;
    }

    /**
     * @param string $imagePath
     * @return bool
     */
    private function removeImageFile(string $imagePath): bool
    {
        try {
            unlink("../$imagePath");
        } catch (Exception) {
            return false;
        }

        return true;
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
