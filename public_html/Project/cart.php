<?php
require(__DIR__ . "/../../partials/nav.php");
require_once(__DIR__ . "/../../lib/functions.php");
?>
<?php
$results = [];
if(isset($_POST["delete"])){
    if(delete_item($_POST["productid"], get_user_id())){
        flash("Item removed from the cart", "success");;
    }
}
if(isset($_POST["empty"])){
    if(empty_cart(get_user_id())){
        flash("All item removed from the cart", "success");;
    }
}
$user_id = get_user_id();
$db = getDB();
$stmt = $db->prepare("SELECT product_id, name, /*user_id,*/ desired_quantity, unit_cost FROM Cart JOIN Products on Cart.product_id = Products.id WHERE user_id = :uid");
try {
    $stmt->execute([":uid" => $user_id]);
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $results = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
    
?>
<script>
    function update_quantity(event,itemid) {
        event.preventDefault()
        var x = event.target.value;
        console.log("TODO Update quantity item", itemid);
            let http = new XMLHttpRequest();
            http.onreadystatechange = () => {
                if (http.readyState == 4) {
                    if (http.status === 200) {
                        let data = JSON.parse(http.responseText);
                        console.log("received data", data);
                        flash(data.message, "success");
                        if (data.message.indexOf("remove")>-1){
                            event.target.parentElement.parentElement.remove();
                        }
                    }
                    console.log(http);
                }
            }
            http.open("POST", "api/update_quantity.php");
            let data = {
                item_id: itemid,
                quantity: x
            } //toegel is amazing 
            let q = Object.keys(data).map(key => key + '=' + data[key]).join('&');
            console.log(q)
            http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            http.send(q);
    }
</script>

<div class="container-fluid">
    <h1>Cart</h1>
        <table class="table text-light">
            <!--<?php print_r($results)  ?>-->
            <?php global $cart_total; ?>
            <?php global $total; ?>
            <?php global $temp_id;?>
            <?php global $temp_quantity;?>
            <?php global $temp_cost;?>
            <?php $temp_quantity = 0;?>
            <?php foreach ($results as $index => $record) : ?> <!-- $result with all the information -->
                <?php if ($index == 0) : ?>       <!-- $index is the number of rows in table  -->
                    <thead>
                            <tr>
                                <th width="35%">Product</th>
                                <th width="8%">Quantity</th>
                                <th width ="15%"> </th>
                                <th width="15%">Price</th>
                                <th class="text-right" width="15%">Total</th>
                            </tr>
                        </thead>
                    <thead>
                        <?php foreach ($record as $column => $value) : ?>
                            <!--<th><?php se($column); ?></th>--><!-- column names "header"-->
                            
                        <?php endforeach; ?>
                    </thead>
                <?php endif; ?>
                <tr>
                    <?php foreach ($record as $column => $value) : ?> <!-- for each row-->
                            <?php if($column == "product_id") :?>
                                <?php $temp_id = $value;?>
                            <?php endif; ?>
                            <?php if($column == "name") :?>
                                <?php $temp_quantity = $value;?>
                                <td><?php echo $value ?></td>
                            <?php endif; ?>
                            <?php if($column == "unit_cost") :?>
                                <?php $temp_cost = $value?>
                                <td> </td>
                                <td><?php echo "$",$value ?></td>
                            <?php endif; ?>
                            <?php if($column == "desired_quantity") :?>
                                <?php $temp_quantity = $value;?>
                                <td><input class="form-control" type="number" id="lname" value="<?php echo $value ;?>" onchange ="update_quantity(event,'<?php se($temp_id); ?>')"/></td>
                            <?php endif; ?>
                            
                        <!--<?php se($value, null, "N/A"); ?></td>--> <!--display values in a row "----"-->  
                    <?php endforeach; ?>
                <?php $total = $temp_quantity * $temp_cost;?> <!--calculating total for each item  -->
                <?php $cart_total = $cart_total + $total;?>
                <td><?php echo "$",$total ?></td>
                <form method="POST">
                <input type="hidden" name="productid" value="<?php echo$temp_id;?>" ></input>
                <td><button type = "submit" class = "btn btn-sm btn-danger" onclick="" name ="delete">Remove</button> </td>
                </form>
                </tr>
            <?php endforeach; ?>
            <div >      
                <thead >
                    <?php if($temp_quantity > 0) :?>
                        <tr >
                            <td></td>
                            <td></td>
                            <td>
                                <th > <?php echo "Cart Total :"?></th>
                            </td>
                                <th> <?php echo "$",$cart_total ?></th>
                                <form method="POST">
                                <td><button type = "submit" name ="empty" class = "btn btn-sm btn-danger" onclick="">Empty the cart</button></td>
                                </form>
                        </tr> 
                    <?php endif; ?>
                </thead>
                <?php if($temp_quantity == 0) :?>
                    <h3 class="text" style="text-align:center"  >Your cart is empty</h3>
                <?php endif; ?>
            </div>
        </table>
        <form action="checkout.php"method="PUT">
                                <!--<input type="hidden" name="ordertotal" value="<?php echo$cart_total;?>" ></input>-->
                                <button style= "margin-top: 30px; width: 200px" type = "submit"  name ="Checkout"  class = "btn btn-primary btn-lg col-12">Checkout</button> 
                                </form> 
</div>
<?php
require(__DIR__ . "/../../partials/footer.php");
?> 