<?php
function convertionRatio(): void
{
    $conn = getDbConnection();
    $sql = "SELECT ratio,update_date FROM convertionratio ORDER BY update_date DESC LIMIT 1";
    $r = $conn->prepare($sql);
    $r->execute();
    $ratio = $r->fetchAll();
    $registered_users = registeredusers($conn);
    $total_orders = totalorders($conn);
    $conn = null;
    if (count($ratio) !== 1) {
        header("Location: " . URL_ROOT . "/404");
        exit();
    }
    require_once('../views/convertionRatio.phtml');
}

function registeredusers($conn)
{
    $prepared = $conn->prepare("SELECT COUNT(*) as user_count, MAX(created_at) as latest_created_at FROM auth");
    $prepared->execute();
if($prepared->rowCount()>0)
{
    return $prepared->fetch(PDO::FETCH_ASSOC);
}
return 0;
}

function totalorders($conn)
{
    $result1 = $conn->prepare("SELECT COUNT(*) as order_count, MAX(order_date) as last_order FROM `order`");
    $result1->execute();

    if($result1->rowCount()>0)
    {

        return $result1->fetch(PDO::FETCH_ASSOC);
    }
    return 0;
}
    ?>;