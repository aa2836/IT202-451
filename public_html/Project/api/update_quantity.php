<?php
// Remember, API endpoints should only echo/output precisely what you want returned
// Any other unexpected characters can break the handling of the response
$response = ["message" => "There was a problem completing your request"];
http_response_code(400);
error_log("req: " . var_export($_POST, true));

if (isset($_POST["item_id"]) && isset($_POST["quantity"])) {

    require_once(__DIR__ . "/../../../lib/functions.php");
    session_start();
    $user_id = get_user_id();
    $item_id = (int)se($_POST, "item_id", 0, false);
    $quantity = (int)se($_POST, "quantity", 0, false);
    $isValid = true;
    $errors = [];

    if ($user_id <= 0) {
        // Invalid user
        array_push($errors, "Invalid user");
        $isValid = false;
    }

    // I'll have predefined items loaded in at negative values
    // so I don't need/want this check
    /*if ($item_id <= 0) {
        // Invalid item
        array_push($errors, "Invalid item");
        $isValid = false;
    }*/

    if ($quantity <= 0) {
        // Invalid quantity
        array_push($errors, "Invalid quantity");
        $isValid = false;
    }

    // Get true price from DB, don't trust the client
    $db = getDB();
    $stmt = $db->prepare("SELECT name, unit_price FROM Products WHERE id = :id");

    try {
        $stmt->execute([":id" => $item_id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            $unit_price = (int)se($r, "unit_price", 0, false);
            $name = se($r, "name", "", false);
        }
    } catch (PDOException $e) {
        error_log("Error getting unit_price of $item_id: " . var_export($e->errorInfo, true));
        $isValid = false;
    }

    if ($isValid) {
        if ($quantity > 0) {
            // Add item to the cart
            add_to_cart($item_id, $user_id, $quantity, $unit_price);
            http_response_code(200);
            $response["message"] = "Added $quantity of $name to cart";
        } else {
            // Delete item from the cart
            delete_item($item_id, $user_id);
            http_response_code(200);
            $response["message"] = "Removed $name from cart";
        }
    } else {
        http_response_code(200);
        $response["message"] = "Log in to add to cart";
    }
}


echo json_encode($response);
