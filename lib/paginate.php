<?php

/**
 * @param $query must have a column called "total"
 * @param array $params
 * @param int $per_page
 */
function paginate($query, $params = [], $per_page = 10)
{
    global $page; //will be available after function is called
    try {
        $page = (int)se($_GET, "page", 1, false);
    } catch (Exception $e) {
        //safety for if page is received as not a number
        $page = 1;
    }
    $db = getDB();
    $stmt = $db->prepare($query);
    try {
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("paginate error: " . var_export($e, true));
    }
    $total = 0;
    if (isset($result)) {
        $total = (int)se($result, "total", 0, false);
    }
    global $total_pages; //will be available after function is called
    $total_pages = ceil($total / $per_page);
    global $offset; //will be available after function is called
    $offset = ($page - 1) * $per_page;
}
//updates or inserts page into query string while persisting anything already present
function persistQueryString($page)
{
    $_GET["page"] = $page;
    return http_build_query($_GET);
}
function order($user_id,$total_price,$full_address,$payment_method)
{
    error_log("add_item() Item ID: user_id: $user_id");
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Orders (user_id, total_price, address, payment_method ) VALUES (:uid, :tp, :a , :pm) ");
    try {
        $stmt->execute([":uid" =>$user_id,":tp" =>$total_price,":a" =>$full_address,":pm" =>$payment_method]);
        return $db->lastInsertId();
    }catch (PDOException $e) {
        error_log("Error adding items to OrderItems table: " . var_export($e->errorInfo, true));
    }
    return false;
}
function order_item($user_id,$order_id)
{
    //error_log("add_item() Item ID: order_id: $order_id");
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO OrderItems (order_id,product_id, quantity, unit_price) SELECT :order_id, product_id , desired_quantity , unit_cost FROM Cart WHERE user_id = :uid");
    //$stmt = $db->prepare("INSERT INTO OrderItems (order_id, product_id, quantity, unit_price) VALUES (:oid, :pid, :q, :up) ");
    try {
        $stmt->execute([":uid"=>$user_id, ":order_id"=>$order_id]);
        return true;
    }catch (PDOException $e) {
        error_log("Error adding items to OrderItems table: " . var_export($e->errorInfo, true));
    }
    return false;
}