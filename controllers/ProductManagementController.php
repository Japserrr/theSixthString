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

    //    /**
    //     * Validate the post request data based on the given fields and type.
    //     * @param string $request
    //     * @param string[] $fields
    //     * @param string $message
    //     * @return array
    //     */
    //    private function validatePostRequest(string $request, array $fields, string $message): array
    //    {
    //        $notification = [
    //            'type' => 'danger',
    //            'message' => $message,
    //        ];
    //
    //        if (empty($_POST[$request])) {
    //            $this->printJson('notification', $notification);
    //            $this->findProducts();
    //            return [];
    //        }
    //
    //        $validatedData = [];
    //        foreach ($fields as $field => $type) {
    //            if (empty($_POST[$request][$field])) {
    //                $this->printJson('notification', $notification);
    //                $this->findProducts();
    //                return [];
    //            }
    //
    //            $value = $_POST[$request][$field];
    //            switch (true) {
    //                case $type === 'string':
    //                    if (!is_string($field)) {
    //                        $this->printJson('notification', $notification);
    //                        $this->findProducts();
    //                        return [];
    //                    }
    //                    $_POST[$request][$field] = trim($value);
    //                    if (empty($_POST[$request[$field]])) {
    //
    //                    }
    //                    break;
    //                case $type ==='numeric':
    //                    if (!is_numeric($field)) {
    //                        $this->printJson('notification', $notification);
    //                        $this->findProducts();
    //                        return [];
    //                    }
    //                    break;
    //                    //TODO make intArray
    //                case $type ==='array':
    //                    if (!is_array($field)) {
    //                        $this->printJson('notification', $notification);
    //                        $this->findProducts();
    //                        return [];
    //                    }
    //                    break;
    //            }
    //
    //            $validatedData += [$field => $_POST[$request][$field]];
    //        }
    //
    //        unset($_POST);
    //
    //        return $validatedData;
    //    }

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

        //        if (!isLoggedIn() || !isAdmin()) {
        //            header('Location: ' . URL_ROOT . '/home');
        //        }

        require_once '../views/management/productManagement.phtml';
    }

    /**
     * @return void
     */
    public function findProducts(): void
    {

        $searchString = null;
        if (!empty($_POST[self::FIND_PRODUCT_POST_REQUEST]['search'])) {
            if (!is_string($_POST[self::FIND_PRODUCT_POST_REQUEST]['search'])) {
                return;
            }
            if ($_POST[self::FIND_PRODUCT_POST_REQUEST]['search']) {
                $searchString = $_POST[self::FIND_PRODUCT_POST_REQUEST]['search'];
                unset($_POST);
            }
        }
        /** TODO add better data validation */
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
        $productData = $_POST[self::ADD_PRODUCT_POST_REQUEST];

        $categoryIds = [];
        if (!empty($productData['categoryIds'])) {
            $categoryIds = $productData['categoryIds'];
        }
        $productData['categoryIds'] = [];
        foreach ($categoryIds as $categoryId) {
            $productData['categoryIds'][] += (int)$categoryId;
        }

        $imageData = $_FILES[self::ADD_PRODUCT_POST_REQUEST];
        $name = $_POST[self::ADD_PRODUCT_POST_REQUEST]['name'];
        /** TODO add better data validation */

        require_once '../models/Product.php';
        $product = new Product();

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
        $productData = $_POST[self::UPDATE_PRODUCT_POST_REQUEST];
        $categoryIds = [];
        if (!empty($productData['categoryIds'])) {
            $categoryIds = $productData['categoryIds'];
        }
        $productData['categoryIds'] = [];
        foreach ($categoryIds as $categoryId) {
            $productData['categoryIds'][] += (int)$categoryId;
        }

        $imageData = $_FILES[self::UPDATE_PRODUCT_POST_REQUEST];
        $name = $_POST[self::UPDATE_PRODUCT_POST_REQUEST]['name'];
        if (empty($productData['imagePath'])) {
            $productData['imagePath'] = null;
        }

        /** TODO add better data validation */

        require_once '../models/Product.php';
        $product = new Product();

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
        $id = $_POST[self::DELETE_PRODUCT_POST_REQUEST]['id'];
        /** TODO add better data validation */

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
        if (
            empty($_POST[self::ADD_BRAND_POST_REQUEST]['name'])
            || !is_string($_POST[self::ADD_BRAND_POST_REQUEST]['name'])
        ) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => 'Er is een fout opgetreden tijdens het toevoegen.']);
            return;
        }
        $name = trim($_POST[self::ADD_BRAND_POST_REQUEST]['name']);
        unset($_POST);
        /** TODO add better data validation */

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
        if (
            empty($_POST[self::DELETE_BRAND_POST_REQUEST]['id'])
            || !is_int((int)$_POST[self::DELETE_BRAND_POST_REQUEST]['id'])
        ) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => "Er is een fout opgetreden tijdens het verwijderen."]);
            return;
        }
        $id = (int)$_POST[self::DELETE_BRAND_POST_REQUEST]['id'];
        unset($_POST);
        /** TODO add better data validation */

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
        if (
            empty($_POST[self::ADD_CATEGORY_POST_REQUEST]['name'])
            || !is_string($_POST[self::ADD_CATEGORY_POST_REQUEST]['name'])
        ) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => 'Er is een fout opgetreden tijdens het toevoegen.']);
            return;
        }
        $name = trim($_POST[self::ADD_CATEGORY_POST_REQUEST]['name']);
        unset($_POST);
        /** TODO add better data validation */

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
        if (
            empty($_POST[self::DELETE_CATEGORY_POST_REQUEST]['id'])
            || !is_int((int)$_POST[self::DELETE_CATEGORY_POST_REQUEST]['id'])
        ) {
            $this->printJavascript('notification', ['type' => 'danger', 'message' => 'Er is een fout opgetreden tijdens het verwijderen.']);
            return;
        }
        $id = (int)$_POST[self::DELETE_CATEGORY_POST_REQUEST]['id'];
        unset($_POST);
        /** TODO add better data validation */

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
