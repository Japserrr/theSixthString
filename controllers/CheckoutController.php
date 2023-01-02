<?php
require_once '../helpers/database.php';
require_once '../helpers/validate.php';

/** @return void */
function checkout(): void
{
//    $userId = isAuthorized();
//    $userId = userId();
    $userId = 1;

    $user['products'] = $_POST['items'] ?? [];
    unset($_POST);

    $conn = getDbConnection();
    $stmt = $conn->prepare('
        SELECT 
            au.email,
            u.first_name AS firstName,
            u.infix AS infix,
            u.last_name AS lastName,
            u.phone_number AS phoneNumber,
            ad.street_name AS streetName,
            ad.zipcode AS zipCode,
            ad.house_number AS houseNumber,
            ad.city,
            ad.country
        FROM auth AS au
        INNER JOIN user AS u ON u.auth_id = au.id 
        LEFT JOIN address AS ad ON ad.id = au.id
        WHERE au.id = :userId
            AND au.active = 1
    ');
    $stmt->execute(['userId' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($result)) {
        $user += $result;
    }
    $conn = null;

    require_once '../views/checkout/checkout.phtml';
}

function confirmPayment(): void
{
//    $userId = isAuthorized();

    $sanitizedData = sanitizeData();

    $orderNumber = registerOrder([
        'userId' => $sanitizedData['userId'],
        'firstName' => $sanitizedData['firstName'],
        'infix' => $sanitizedData['infix'],
        'lastName' => $sanitizedData['lastName'],
        'email' => $sanitizedData['email'],
        'phoneNumber' => $sanitizedData['phoneNumber'],
        'zipCode' => $sanitizedData['zipCode'],
        'streetName' => $sanitizedData['streetName'],
        'houseNumber' => $sanitizedData['houseNumber'],
        'city' => $sanitizedData['city'],
        'country' => $sanitizedData['country'],
        'bankName' => $sanitizedData['bankName'],
        'bankAccount' => $sanitizedData['bankAccount'],
        'totalPrice' => $sanitizedData['totalPrice'],
        'products' => $sanitizedData['products'],
    ]);

    $order = getOrder($orderNumber);

    require_once '../views/checkout/mail.phtml';
}

/** === Utils === */

/** @return int */
function isAuthorized(): int
{
    $userId = userId();
    if (!isLoggedIn() || !$userId) {
        header('Location: '.URL_ROOT.'/home');
    }
    return $userId;
}

/**
 * @return array
 */
function sanitizeData(): array
{
    if (empty($_POST)) {
        header('Location: '.URL_ROOT.'/checkout');
    }
    $request = $_POST;
    unset($_POST);

    if (empty($request['userId'])
        || empty($request['firstName'])
        || empty($request['lastName'])
        || empty($request['email'])
        || empty($request['zipCode'])
        || empty($request['streetName'])
        || empty($request['houseNumber'])
        || empty($request['city'])
        || empty($request['country'])
        || empty($request['bankName'])
        || empty($request['products'])
    ) {
        header('Location: '.URL_ROOT.'/checkout');
    }

    if (!is_numeric($request['userId'])
        || !is_string($request['firstName'])
        || (!is_string($request['infix']))
        || !is_string($request['lastName'])
        || !is_string($request['email'])
        || !is_numeric($request['phoneNumber'])
        || !is_string($request['zipCode'])
        || !is_string($request['streetName'])
        || !is_string($request['houseNumber'])
        || !is_string($request['city'])
        || !is_string($request['country'])
        || !is_string($request['bankName'])
    ) {
        header('Location: '.URL_ROOT.'/checkout');
    }

    $i = 1;
    $request['bankAccount'] = '';
    while ($i < 19) {
        if (empty($request["bankAccount$i"])) {
            header('Location: '.URL_ROOT.'/checkout');
        }
        $request['bankAccount'] .= $request["bankAccount$i"];
        unset($request["bankAccount$i"]);
        $i++;
    }

    $request['totalPrice'] = 0;
    foreach ($request['products'] as $product) {
        if (!is_numeric($product['id'])
            || !is_numeric($product['price'])
        ) {
            header('Location: '.URL_ROOT.'/checkout');
        }
        $request['totalPrice'] += $product['price'];
    }

    return $request;
}

/**
 * @param array{
 *     userId: int,
 *     firstName: string,
 *     infix: string|null,
 *     lastName: string,
 *     email: string,
 *     phoneNumber: int,
 *     zipCode: string,
 *     streetName: string,
 *     houseNumber: string,
 *     city: string,
 *     country: string,
 *     bankName: string,
 *     bankAccount: string,
 *     totalPrice: float,
 *     products: array{id: int, price: float},
 * } $order
 * @return int
 */
function registerOrder(array $order): int
{
    $conn = getDbConnection();
    $lastOrderNumber = $conn->query('
        SELECT id
        FROM `order`
        ORDER BY id DESC
    ')->fetchColumn();

    /**
     * Order format is day + month + year + number of the order.
     * So for example: 2712221 gives 27-12-2022 as the date and 1 is the actual number of the order.
     */
    if (!$lastOrderNumber) {
        $orderNumber = (date('dmy') . 1);
    } else {
        $latestOrderId = substr((int)$lastOrderNumber, 6);
        $orderNumber = (date('dmy') . $latestOrderId + 1);
    }

    $stmt = $conn->prepare('
        INSERT INTO payment
        VALUES (null, :bankName,:bankAccount, :price)
    ');
    $stmt->execute([
        'bankName' => $order['bankName'],
        'bankAccount' => $order['bankAccount'],
        'price' => $order['totalPrice'],
    ]);
    $paymentId = $conn->lastInsertId();

    $stmt = $conn->prepare('
        SELECT id 
        FROM address
        WHERE street_name = :streetName
            AND zipcode = :zipcode,
            AND house_number = :houseNumber,
            AND city = :city,
            AND country = :country,
    ');
    $stmt->execute([
        'streetName' => $order['streetName'],
        'zipcode' => $order['zipcode'],
        'houseNumber' => $order['houseNumber'],
        'city' => $order['city'],
        'country' => $order['country'],
    ]);
    $shippingAddressId = $stmt->fetchColumn();
    if (empty($shippingAddressId)) {
        $stmt = $conn->prepare('
            INSERT INTO address 
            VALUES (null, :streetName, :zipcode, :houseNumber, :city, :country)
        ');
            $stmt->execute([
                'streetName' => $order['streetName'],
                'zipcode' => $order['zipcode'],
                'houseNumber' => $order['houseNumber'],
                'city' => $order['city'],
                'country' => $order['country'],
            ]);
        $shippingAddressId = $conn->lastInsertId();
    }

    $stmt = $conn->prepare('
        INSERT INTO `order`
        VALUES (:orderNumber, :userId, :paymentId, :shippingAddressId, :orderDateTime)
    ');
    $stmt->execute([
        'orderNumber' => $orderNumber,
        'userId' => $order['userId'],
        'paymentId' => $paymentId,
        'shippingAddressId' => $shippingAddressId,
        'orderDateTime' => date('Y-m-d H:i:s'),
    ]);

    foreach ($order['productIds'] as $productId) {
        $stmt = $conn->prepare('
            INSERT INTO order_has_products 
            VALUES (:orderNumber, :productId)
        ');
        $stmt->execute([
            'orderNumber' => $orderNumber,
            'productId' => $productId
        ]);
    }
    $conn = null;

    return $orderNumber;
}

/**
 * @param int $orderNumber
 * @return array
 */
function getOrder(int $orderNumber): array
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("
        SELECT
            u.auth_id AS userId,
            CONCAT(u.first_name, ' ', IF(u.infix IS NOT NULL, u.infix, ''), IF(u.infix IS NOT NULL, ' ', ''), u.last_name) AS fullName,
            au.email,
            u.phone_number AS phoneNumber,
            ad.zipcode AS zipCode,
            ad.street_name AS streetName,
            ad.house_number AS houseNumber,
            ad.city,
            ad.country,
            p.bank_account AS bankAccount,
            p.amount AS totalPrice,
            o.id AS orderNumber,
            o.order_date AS orderDateTime
        FROM `order` AS o 
        INNER JOIN user AS u ON u.auth_id = o.customer_id
        INNER JOIN auth AS au ON au.id = u.auth_id
        INNER JOIN address AS ad ON ad.id = o.shipping_address_id
        INNER JOIN payment AS p ON p.id = o.payment_id
        WHERE o.id = :orderNumber
    ");
    $stmt->execute([
        'orderNumber' => $orderNumber,
    ]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("
        SELECT
            p.product_name AS name,
            p.price,
            COUNT(ohp.product_id) AS amount
        FROM product AS p
        INNER JOIN order_has_products AS ohp ON ohp.product_id = p.id
        WHERE ohp.order_id = :orderNumber
        GROUP BY ohp.product_id
    ");
    $stmt->execute([
        'orderNumber' => $orderNumber,
    ]);
    $order['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;

    return $order;
}