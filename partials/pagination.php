<?php
//safety checks to ensure "valid" data
if (!isset($total_pages)) {
    $total_pages = 1;
}
if (!isset($page)) {
    $page = 1;
}
if (!isset($itemsPerPage) || $itemsPerPage == 0) {
    die(" ");
}
?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php
            $totalPages = ceil($totalItems / $itemsPerPage); // This is the line where division happens
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<li class="page-item' . ($page == $i ? ' active' : '') . '">';
                echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                echo '</li>';
            }
        ?>
    </ul>
</nav>