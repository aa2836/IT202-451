<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<?php 
    $result = [];
    $item_id = se($_GET, "id", 0, false);
    $columns = get_columns("Products");
    $ignore = ["id", "modified", "created"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Products where id =:id");
    try {
    $stmt->execute([":id" => $item_id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            $result = $r;
        }
    }catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
    function mapColumn($col)
    {
        global $columns;
        foreach ($columns as $c) {
            if ($c["Field"] === $col) {
                return inputMap($c["Type"]);
           }
        }
        return "text";
    }

    
?>

<div class="container-fluid">
<div class ="row">
    <div class = "col-3">
    <?php global $temp_id; ?>
    <?php global $a_r; ?>
    <?php global $p_id; ?>
    <h1>Product Detail</h1>
        <?php foreach ($result as $column => $value) : ?>
            <?php if ($column == "name"):  ?>
                <h5 class="body1">Name: <?php se($value, "name"); ?></h5>
            <?php endif; ?>
            <?php if ($column == "image"):  ?>
                <img class="body" src="<?php se($value, "image"); ?>" alt="...">
            <?php endif; ?>
            <?php if ($column == "description"):  ?>
                <h5 class="body">Description: <?php se($value, "description"); ?></h5>
            <?php endif; ?>
            <?php if ($column == "id"):  ?>
                <?php $p_id = $value?>
            <?php endif; ?>
            <?php if ($column == "average_rating"):  ?>
                <?php $a_r = $value?>
            <?php endif; ?>
            <?php if ($column == "cost"):  ?>
                <h5 class="body">Cost: $<?php se($value, "cost"); ?></h5>
            <?php endif; ?>
            <?php if ($column == "category"):  ?>
                <h5 class="body">Category: <?php se($value, "category"); ?></h5>
            <?php endif; ?>
            <?php if ($column == "stock"):  ?>
                <h5 class="body">Stock: <?php se($value, "stock"); ?></h5>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if (has_role("Admin")) : ?> 
            <td>
                <a href="admin/edit_item.php?id=<?php se($item_id, "id"); ?>">
                <button class="btn btn-primary">Edit</button>
            </a>
            </td>
        <?php endif; ?>
        
            <?php ; ?>

<?php include(__DIR__ . "/../../partials/pagination.php"); ?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>