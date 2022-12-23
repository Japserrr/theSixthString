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

    /**
     * @param string|null $searchString
     * @return array
     */
    public function findAll(?string $searchString = null): array
    {
        $sql = '
            SELECT 
                p.id AS id, 
                p.product_name AS name,
                b.id AS brandId,
                p.price,
                p.quantity,
                p.description,
                p.video_url AS videoUrl,
                p.img_path AS imagePath
            FROM product AS p 
            LEFT JOIN brand AS b ON b.id  = p.brand_id
        ';
        if ($searchString) {
            $sql .= '
                WHERE p.product_name LIKE :search
                OR b.brand_name LIKE :search
                OR p.description LIKE :search
            ';
            $params = ['search' => "%$searchString%"];
        }
        try {
            $conn = getDbConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($params ?? null);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conn = null;
        } catch (Exception) {
            return [];
        }
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
            /** TODO update product_category table */
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

            if (empty($product['imagePath'])) {
                $stmt = $conn->prepare('SELECT img_path AS imagePath FROM product WHERE id = :id AND img_path IS NOT NULL');
                $stmt->execute(['id' => $product['id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($result['imagePath'])) {
                    if (!$this->removeImageFile($result['imagePath'])) {
                        return false;
                    }
                }
            }

            if (!empty($image['name']['image'])) {
                $newImagePath = $this->saveImageFile($image, $product['name']);
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
                'imagePath' => $product['imagePath'] ?? $newImagePath ?? null,
            ]);

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

            $stmt = $conn->prepare('DELETE FROM product WHERE id = :productId');
            $stmt->execute(['productId' => $productId]);

            $stmt = $conn->prepare('DELETE FROM product_category WHERE product_id = :productId');
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
            $imageName = str_replace(' ', '-', $productName);
            $dateTimeString = str_replace(' ', '_', date('dmyHis'));
            $imageFileInfo = pathinfo($image['name']['image']);

            $newImagePath = "./public/img/products/$imageName" . "_$dateTimeString." . $imageFileInfo['extension'];
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
            unlink($imagePath);
        } catch (Exception) {
            return false;
        }

        return true;
    }
}
