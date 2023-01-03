<?php

class ProductManagementController
{

    /** Product request keys */
    public const FIND_PRODUCT_POST_REQUEST = 'productManagementFind';
    public const ADD_PRODUCT_POST_REQUEST = 'productManagementAdd';
    public const UPDATE_PRODUCT_POST_REQUEST = 'productManagementUpdate';
    public const DELETE_PRODUCT_POST_REQUEST = 'productManagementDelete';

    /** Brand request keys */
    public const FIND_BRAND_POST_REQUEST = 'brandManagementFind';
    public const ADD_BRAND_POST_REQUEST = 'brandManagementAdd';
    public const DELETE_BRAND_POST_REQUEST = 'brandManagementDelete';

    /** Category request keys */
    public const FIND_CATEGORY_POST_REQUEST = 'categoryManagementFind';
    public const ADD_CATEGORY_POST_REQUEST = 'categoryManagementAdd';
    public const DELETE_CATEGORY_POST_REQUEST = 'categoryManagementDelete';

    /** @return void */
    function isAuthorized(): void
    {
        if (!isLoggedIn() || !isAdmin()) {
            header('Location: ' . URL_ROOT . '/home');
        }
    }

    /**
     * @param array{
     *     key: string,
     *     required: bool,
     *     type: string,
     *     request: mixed
     * } $data
     * @return string|int
     */
    private function serializeData(array $data): string|int
    {
        $key = $data['request'][$data['key']];

        if ($data['required'] && empty($key)) {
            header('Location: ./product-management');
        }

        switch ($data['type']) {
            case 'string':
                if (!is_string($key)) {
                    header('Location: ./product-management');
                }
                $value = trim($key);
                break;
            case 'numeric':
                if (!is_numeric($key)) {
                    header('Location: ./product-management');
                }
                $value = (int) trim($key);
                break;
        }

        return $value;
    }

    /**
     * @param string $variableName
     * @param mixed $data
     * @return void
     */
    private function printJavascript(string $variableName, mixed $data): void
    {
        switch (true) {
            case is_array($data):
?>
                <script type="text/javascript">
                    const <?= $variableName; ?> = <?= json_encode($data); ?>;
                </script>
            <?php
                break;
            case is_string($data):
            ?>
                <script type="text/javascript">
                    const <?= $variableName; ?> = '<?= $data; ?>';
                </script>
            <?php
                break;
            default:
            ?>
                <script type="text/javascript">
                    const <?= $variableName; ?> = <?= $data; ?>;
                </script>
<?php
                break;
        }
    }

    /** ======== Product management ======== */

    /**
     * @return void
     */
    public function productManagement(): void
    {
        $this->isAuthorized();

        require_once '../views/management/productManagement.phtml';
    }

    /**
     * @return void
     */
    public function findProducts(): void
    {
        $searchString = null;
        if (!empty($_POST[self::FIND_PRODUCT_POST_REQUEST]['search'])) {
            $searchString = $_POST[self::FIND_PRODUCT_POST_REQUEST]['search'];
            unset($_POST);
        }

        require_once '../models/Product.php';

        if ($searchString) {
            $this->printJavascript('search', $searchString);
            $this->printJavascript('products', (new Product())->findAll($searchString));
        } else {
            $this->printJavascript('products', (new Product())->findAll());
        }
    }

    /**
     * @return void
     */
    public function addProduct(): void
    {
        if (
            empty($_POST[self::ADD_PRODUCT_POST_REQUEST])
            || empty($_FILES[self::ADD_PRODUCT_POST_REQUEST])
        ) {
            header('Location: ./product-management');
        }

        if (empty($_POST[self::ADD_PRODUCT_POST_REQUEST]['categoryIds'])) {
            $categoryIds = [];
        } else {
            foreach ($_POST[self::ADD_PRODUCT_POST_REQUEST]['categoryIds'] as $categoryId) {
                if (!is_numeric($categoryId)) {
                    header('Location: ./product-management');
                }
            }
            $categoryIds = $_POST[self::ADD_PRODUCT_POST_REQUEST]['categoryIds'];
        }

        $productData = [
            'name' => $this->serializeData(['key' => 'name', 'required' => true, 'type' => 'string', 'request' => $_POST[self::ADD_PRODUCT_POST_REQUEST]]),
            'brandId' => $this->serializeData(['key' => 'brandId', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::ADD_PRODUCT_POST_REQUEST]]),
            'quantity' => $this->serializeData(['key' => 'quantity', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::ADD_PRODUCT_POST_REQUEST]]),
            'price' => $this->serializeData(['key' => 'price', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::ADD_PRODUCT_POST_REQUEST]]),
            'categoryIds' => $categoryIds,
            'description' => $this->serializeData(['key' => 'description', 'required' => false, 'type' => 'string', 'request' => $_POST[self::ADD_PRODUCT_POST_REQUEST]]),
            'videoUrl' => $this->serializeData(['key' => 'videoUrl', 'required' => false, 'type' => 'string', 'request' => $_POST[self::ADD_PRODUCT_POST_REQUEST]]),
        ];

        $imageData = $_FILES[self::ADD_PRODUCT_POST_REQUEST];

        require_once '../models/Product.php';
        $product = new Product();

        $name = $productData['name'];
        if ($product->nameExists($name)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Merknaam bestaat al."]);
            return;
        }
        if (!$product->add($productData, $imageData)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Er is een fout opgetreden tijdens het toevoegen van <strong>$name</strong>."]);
            return;
        }
        $this->printJavascript('notification', ['type' => 'success', 'message' => "<strong>$name</strong> is toegevoegd."]);
    }

    /**
     * @return void
     */
    public function updateProduct(): void
    {
        if (
            empty($_POST[self::UPDATE_PRODUCT_POST_REQUEST])
            || empty($_FILES[self::UPDATE_PRODUCT_POST_REQUEST])
        ) {
            header('Location: ./product-management');
        }

        $imagePath = $this->serializeData(['key' => 'imagePath', 'required' => false, 'type' => 'string', 'request' => $_POST[self::UPDATE_PRODUCT_POST_REQUEST]]);
        if (empty($imagePath)) {
            $imagePath = null;
        }

        if (empty($_POST[self::UPDATE_PRODUCT_POST_REQUEST]['categoryIds'])) {
            $categoryIds = [];
        } else {
            foreach ($_POST[self::UPDATE_PRODUCT_POST_REQUEST]['categoryIds'] as $categoryId) {
                if (!is_numeric($categoryId)) {
                    header('Location: ./product-management');
                }
            }
            $categoryIds = $_POST[self::UPDATE_PRODUCT_POST_REQUEST]['categoryIds'];
        }

        $productData = [
            'id' => $this->serializeData(['key' => 'id', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::UPDATE_PRODUCT_POST_REQUEST]]),
            'name' => $this->serializeData(['key' => 'name', 'required' => true, 'type' => 'string', 'request' => $_POST[self::UPDATE_PRODUCT_POST_REQUEST]]),
            'brandId' => $this->serializeData(['key' => 'brandId', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::UPDATE_PRODUCT_POST_REQUEST]]),
            'quantity' => $this->serializeData(['key' => 'quantity', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::UPDATE_PRODUCT_POST_REQUEST]]),
            'price' => $this->serializeData(['key' => 'price', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::UPDATE_PRODUCT_POST_REQUEST]]),
            'categoryIds' => $categoryIds,
            'description' => $this->serializeData(['key' => 'description', 'required' => false, 'type' => 'string', 'request' => $_POST[self::UPDATE_PRODUCT_POST_REQUEST]]),
            'videoUrl' => $this->serializeData(['key' => 'videoUrl', 'required' => false, 'type' => 'string', 'request' => $_POST[self::UPDATE_PRODUCT_POST_REQUEST]]),
            'imagePath' => $imagePath,
        ];

        $imageData = $_FILES[self::UPDATE_PRODUCT_POST_REQUEST];

        require_once '../models/Product.php';
        $product = new Product();

        $name = $productData['name'];
        if (!$product->update($productData, $imageData)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Er is een fout opgetreden tijdens het wijzigen van <strong>$name</strong>."]);
            return;
        }
        $this->printJavascript('notification', ['type' => 'success', 'message' => "<strong>$name</strong> is gewijzigd."]);
    }

    /**
     * @return void
     */
    public function deleteProduct(): void
    {
        if (empty($_POST[self::DELETE_PRODUCT_POST_REQUEST])) {
            header('Location: ./product-management');
        }

        $id = $this->serializeData(['key' => 'id', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::DELETE_PRODUCT_POST_REQUEST]]);

        require_once '../models/Product.php';
        $product = new Product();

        $name = $product->findById($id);
        if (empty($name)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => 'Product niet gevonden.']);
            return;
        }
        if (!$product->delete($id)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Er is een fout opgetreden tijdens het verwijderen van <strong>$name</strong>."]);
            return;
        }
        $this->printJavascript('notification', ['type' => 'success', 'message' => "<strong>$name</strong> is verwijderd."]);
    }

    /** ======== Brand management ======== */

    /**
     * @return void
     */
    public function findBrands(): void
    {
        require_once '../models/Brand.php';

        $this->printJavascript('brands', (new Brand())->findAll());
    }

    /**
     * @return void
     */
    public function addBrand(): void
    {
        if (empty($_POST[self::ADD_BRAND_POST_REQUEST])) {
            header('Location: ./product-management');
        }

        $name = $this->serializeData(['key' => 'name', 'required' => true, 'type' => 'string', 'request' => $_POST[self::ADD_BRAND_POST_REQUEST]]);

        require_once '../models/Brand.php';
        $brand = new Brand();

        if ($brand->nameExists($name)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Merknaam bestaat al."]);
            return;
        }
        if (!$brand->add($name)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Er is een fout opgetreden tijdens het toevoegen van <strong>$name</strong>."]);
            return;
        }
        $this->printJavascript('notification', ['type' => 'success', 'message' => "<strong>$name</strong> is toegevoegd."]);
    }

    /**
     * @return void
     */
    public function deleteBrand(): void
    {
        if (empty($_POST[self::DELETE_BRAND_POST_REQUEST])) {
            header('Location: ./product-management');
        }

        $id = $this->serializeData(['key' => 'id', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::DELETE_BRAND_POST_REQUEST]]);

        require_once '../models/Brand.php';
        $brand = new Brand();

        $name = $brand->findById($id);
        if (empty($name)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Merk niet gevonden."]);
            return;
        }
        if ($brand->isUsed($id)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "<strong>$name</strong> is gekoppeld aan een product en kan niet worden verwijderd."]);
            return;
        }
        if (!$brand->delete($id)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Er is een fout opgetreden tijdens het verwijderen van <strong>$name</strong>."]);
            return;
        }
        $this->printJavascript('notification', ['type' => 'success', 'message' => "<strong>$name</strong> is verwijderd."]);
    }

    /** ======== Category management ======== */

    public function findCategories(): void
    {
        require_once '../models/Category.php';

        $this->printJavascript('categories', (new Category())->findAll());
    }
    /**
     * @return void
     */
    public function addCategory(): void
    {
        if (empty($_POST[self::ADD_CATEGORY_POST_REQUEST])) {
            header('Location: ./product-management');
        }

        $name = $this->serializeData(['key' => 'name', 'required' => true, 'type' => 'string', 'request' => $_POST[self::ADD_CATEGORY_POST_REQUEST]]);

        require_once '../models/Category.php';
        $category = new Category();

        if ($category->nameExists($name)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => 'Categorie naam bestaat al.']);
            return;
        }
        if (!$category->add($name)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Er is een fout opgetreden tijdens het toevoegen van <strong>$name</strong>."]);
            return;
        }
        $this->printJavascript('notification', ['type' => 'success', 'message' => "<strong>$name</strong> is toegevoegd."]);
    }

    /**
     * @return void
     */
    public function deleteCategory(): void
    {
        if (empty($_POST[self::DELETE_CATEGORY_POST_REQUEST])) {
            header('Location: ./product-management');
        }

        $id = $this->serializeData(['key' => 'id', 'required' => true, 'type' => 'numeric', 'request' => $_POST[self::DELETE_CATEGORY_POST_REQUEST]]);

        require_once '../models/Category.php';
        $category = new Category();

        $name = $category->findById($id);
        if (empty($name)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => 'Categorie niet gevonden.']);
            return;
        }
        if ($category->isUsed($id)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "<strong>$name</strong> is gekoppeld aan een product en kan niet worden verwijderd."]);
            return;
        }
        if (!$category->delete($id)) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Er is een fout opgetreden tijdens het toevoegen van <strong>$name</strong>."]);
            return;
        }
        $this->printJavascript('notification', ['type' => 'success', 'message' => "<strong>$name</strong> is verwijderd."]);
    }
}
