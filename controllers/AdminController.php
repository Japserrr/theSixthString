<?php
function convertionRatio(): void
{
    $conn = getDbConnection();
    $registered_users = registeredusers($conn);
    $total_orders = totalorders($conn);
    $ratio = ($registered_users)["user_count"] > 1 ? 100 / intval(($registered_users)["user_count"]) * intval(($total_orders)["order_count"]) : 0;
    $conn = null;
    require_once('../views/statistics.phtml');
}

function registeredusers($conn)
{
    $prepared = $conn->prepare("SELECT COUNT(*) as user_count, MAX(created_at) as latest_created_at FROM auth");
    $prepared->execute();
    if ($prepared->rowCount() > 0) {
        return $prepared->fetch(PDO::FETCH_ASSOC);
    }
    return 0;
}

function totalorders($conn)
{

    $result1 = $conn->prepare("SELECT COUNT(*) as order_count, MAX(order_date) as last_order FROM `order`");
    $result1->execute();

    if ($result1->rowCount() > 0) {

        return $result1->fetch(PDO::FETCH_ASSOC);
    }
    return 0;
}
