<?php
ob_start();
    session_start();
$pageTitle = 'Homepage';
    include 'init.php'; ?>

    <div class="container">
        <div class="row">
            <?php
                $allItems = getAllFrom('*', 'items', 'where Approve = 1', '', 'itemID');
            foreach ($allItems as $item) {
                echo '<div class="col-sm-6 col-md-4">';
                echo '<div class="thumbnail item-box">';
                echo '<span class= price-tag>$' . $item['Price'] . '</span>';
                echo '<img class="img-responsive" src="img.png" alt="">';
                echo '<div class="caption">';
                echo '<h3><a href="items.php?itemID=' . $item['itemID'] . '">' . $item['Name'] . '</a></h3>';
                echo '<p>' . $item['Description'] . '</p>';
                echo '<div class="date">' . $item['AddedDate'] . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>


    <?php
include $tpl . 'footer.php';
ob_end_flush();
?>