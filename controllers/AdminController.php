<?php
function convertionRatio(): void
{
    $conn = getDbConnection();
    $registered_users = getStatistics($conn, "SELECT COUNT(*) as order_count, MAX(order_date) as last_order FROM `order`");
    $total_orders = getStatistics($conn, "SELECT COUNT(*) as user_count, MAX(created_at) as latest_created_at FROM auth");
    $ratio = ($registered_users)["user_count"] > 1 ? 100 / intval(($registered_users)["user_count"]) * intval(($total_orders)["order_count"]) : 0;
    $conn = null;
    require_once('../views/statistics.phtml');
}


function getStatistics($conn, $query): array|int
{
    $result1 = $conn->prepare($query);
    $result1->execute();

    if ($result1->rowCount() > 0) {

        return $result1->fetch(PDO::FETCH_ASSOC);
    }
    return 0;
}
