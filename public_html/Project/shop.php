<?php
require(__DIR__ . "/../../partials/nav.php");

$itemsPerPage = 4; // Number of items to display per page

// Get the current page number from the URL or set a default value of 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;

$filterName = $_GET['filter_name'] ?? '';
$sortBy = $_GET['sort_by'] ?? 'name'; // default sort by name

$results = [];
$db = getDB();
$totalItems = 0;

$query = "SELECT SQL_CALC_FOUND_ROWS id, name, description, cost, stock, image 
          FROM Products 
          WHERE stock > 0";

if (!empty($filterName)) {
    $query .= " AND name LIKE :filterName";
}

switch ($sortBy) {
    case 'cost':
        $query .= " ORDER BY cost ASC";
        break;
    case 'category':
        $query .= " ORDER BY category ASC"; // Assuming the column name in your database is "category".
        break;
    default:
        $query .= " ORDER BY name ASC";  // defaults to name if none of the criteria match.
        break;
}

$query .= " LIMIT :offset, :itemsPerPage";

$stmt = $db->prepare($query);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
if (!empty($filterName)) {
    $filterTerm = "%" . $filterName . "%";
    $stmt->bindParam(':filterName', $filterTerm, PDO::PARAM_STR);
}

try {
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $results = $r;
    }

    // Get the total number of items in the database (without the LIMIT)
    $stmtTotal = $db->query("SELECT FOUND_ROWS()");
    $totalItems = intval($stmtTotal->fetchColumn());
} catch (PDOException $e) {
    error_log(var_export($e, true));
    flash("Error fetching items", "danger");
}
?>

<div class="container-fluid">
    <h1>World Soccer Shop</h1>

    <!-- Filter & Sort UI -->
    <form method="GET" action="">
        <div class="row">
            <div class="col-md-4">
                <label>Filter by name:</label>
                <input type="text" name="filter_name" value="<?php echo htmlspecialchars($filterName); ?>"/>
            </div>
            <div class="col-md-4">
                <label>Sort by:</label>
                <select name="sort_by">
                    <option value="catagory" <?php echo $sortBy == 'catagory' ? 'selected' : ''; ?>>Catagory</option>
                    <option value="cost" <?php echo $sortBy == 'cost' ? 'selected' : ''; ?>>Price</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="submit" value="Apply" class="btn btn-primary mt-2"/>
            </div>
        </div>
    </form>

    <div class="row row-cols-sm-2 row-cols-xs-1 row-cols-md-3 row-cols-lg-6 g-4">
    <?php foreach ($results as $item) : ?>
        <div class="col">
            <div class="card bg-light">
                <div class="card-header">
                    RM Placeholder
                </div>
                <?php if (se($item, "image", "", false)) : ?>
                    <img src="<?php se($item, "image"); ?>" class="card-img-top" alt="...">
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title">Name: <?php se($item, "name"); ?></h5>
                    <p class="card-text">Description: <?php se($item, "description"); ?></p>
                </div>
                <div class="card-footer">
                    Cost: <?php se($item, "cost"); ?>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="item_id" value="<?php se($item, "id"); ?>"/>
                        <input type="hidden" name="action" value="add"/>
                        <input type="number" name="desired_quantity" value="1" min="1" max="<?php se($item, "stock"); ?>"/>
                        <input type="submit" class="btn btn-primary" value="Add to Cart"/>
                    </form>
                    <a href="product_details.php?id=<?php se($item, 'id'); ?>" class="btn btn-info">Details</a>
                    <?php if (has_role("Admin")): ?>
                        <a href="admin/edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-warning">Edit</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    <!-- Pagination -->
    <?php if ($totalItems > $itemsPerPage) : ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                $totalPages = ceil($totalItems / $itemsPerPage);
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<li class="page-item' . ($page == $i ? ' active' : '') . '">';
                    echo '<a class="page-link" href="?page=' . $i . '&filter_name=' . urlencode($filterName) . '&sort_by=' . urlencode($sortBy) . '">' . $i . '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php require(__DIR__ . "/../../partials/footer.php"); 
?>

