
<?php
function update_cart($item_id, $user_id, $quantity)
{
    error_log("add_item() Item ID: $item_id, User_id: $user_id, Quantity $quantity");
    $db = getDB();
    $stmt = $db->prepare("UPDATE Cart set desired_quantity = :q where product_id = :cart_id and user_id=:uid");
    try {
        $stmt->execute([":q" => $quantity,":cart_id" => $item_id ,":uid"=>$user_id]);
        return true;
    }catch (PDOException $e) {
        error_log("Error adding $quantity of $item_id to user $user_id: " . var_export($e->errorInfo, true));
    }
    return false;
}
function delete_item($item_id, $user_id)
{
    error_log("add_item() Item ID: $item_id, User_id: $user_id");
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM Cart where product_id = :cart_id and user_id=:uid");
    try{
        $stmt->execute([":cart_id" => $item_id, ":uid" => $user_id]);
        return true;
    }catch (PDOException $e) {
        error_log("Error deleting $item_id to user $user_id: " . var_export($e->errorInfo, true));
    }
    return false;
}
function empty_cart($user_id)
{
    error_log("add_item() Item ID: User_id: $user_id");
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM Cart where user_id = :uid");
    try{
        $stmt->execute([":uid" =>$user_id]);
        return true;
    }catch (PDOException $e){
        error_log("Error empting cart of $user_id: " . var_export($e->errorInfo, true));
    }
    return false;
}
function add_to_cart($item_id, $user_id, $quantity, $cost)
{
    //I'm using negative values for predefined items so I can't validate >= 0 for item_id
    if ($user_id <= 0 || $quantity === 0) {
        error_log("add_to_cart() Item ID: $item_id, User_id: $user_id, Quantity $quantity");
        return;
    }
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Cart (product_id, user_id, desired_quantity, unit_cost) VALUES (:iid, :uid, :q, :uc) ON DUPLICATE KEY UPDATE desired_quantity = desired_quantity + :q, unit_cost=:uc");
    try {
        $stmt->execute([":iid" => $item_id, ":uid" => $user_id, ":q" => $quantity, ":uc" => $cost]);
        return true;
    } catch (PDOException $e) {
        error_log("Error recording purchase $quantity of $item_id for user $user_id: " . var_export($e->errorInfo, true));
    }
    return false;
}

function updating_stock($product_id,$product_stock,$OrderItems_quantity){
    $db = getDB();
    $stmt = $db->prepare("UPDATE Products set stock = :p_s - :Oi_q where id = :p_id ");
    try {
        $stmt->execute([":p_id"=>$product_id, ":p_s"=>$product_stock, ":Oi_q"=>$OrderItems_quantity]);
        return true;
    }catch (PDOException $e) {
        error_log("Error adding items to OrderItems table: " . var_export($e->errorInfo, true));
    }
    return false;
}