<?php


/*
================================================
== Items Page
================================================
*/

ob_start(); // Output Buffering Start

session_start();

$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {
        $stmt = $dbconc->prepare("SELECT items.* , categories.catName , users.userName FROM items
                                            INNER JOIN categories
                                            ON categories.catID = items.Cat_ID
                                            INNER JOIN users
                                            where users.userID = items.Member_ID;");
        $stmt->execute();
        $items = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="  table-responsive ">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Added Date</td>
                        <td>Member</td>
                        <td>Categorie</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($items as $item) {
                        echo "<tr>";
                        echo "<td>" . $item['itemID'] . "</td>";
                        echo "<td>" . $item['Name'] . "</td>";
                        echo "<td>" . $item['Description'] . "</td>";
                        echo "<td>" . $item['Price'] . "</td>";
                        echo "<td>" . $item['AddedDate'] . "</td>";
                        echo "<td>" . $item['userName'] . "</td>";
                        echo "<td>" . $item['catName'] . "</td>";
                        echo "<td>
                            <a href='items.php?do=Edit&itemID=" . $item['itemID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='items.php?do=Delete&itemID=" . $item['itemID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                        if ($item['Approve'] == 0){
                            echo "<a href='items.php?do=Approve&itemID=" . $item['itemID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                        }
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Item</a>
        </div>

    <?php } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Items</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Item name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text"
                               name="Name"
                               class="form-control"
                               required="required"
                               placeholder="Name of the item"/>
                    </div>
                </div>
                <!-- End Item name Field -->
                <!-- Start Item description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description </label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text"
                               name="description"
                               class="form-control"
                               required="required"
                               placeholder="Description of the item"/>
                    </div>
                </div>
                <!-- End Item description Field -->
                <!-- Start Item price Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text"
                               name="price"
                               class="form-control"
                               required="required"
                               placeholder="price of the item"/>
                    </div>
                </div>
                <!-- End Item price Field -->
                <!-- Start Item country Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text"
                               name="country"
                               class="form-control"
                               required="required"
                               placeholder="Country of the item"/>
                    </div>
                </div>
                <!-- End Item country Field -->
                <!-- Start Item status Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name = "status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Semi-New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Item status Field -->

                <!-- Start Members Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Members</label>
                    <div class="col-sm-10 col-md-6">
                        <select name = "members">
                            <option value="0">...</option>
                            <?php
                                $stmt = $dbconc->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user){
                                    echo "<option value='". $user['userID']."'>".$user['userName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Members Field -->
                <!-- Start categories Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Categories</label>
                    <div class="col-sm-10 col-md-6">
                        <select name = "categories">
                            <option value="0">...</option>
                            <?php
                                $stmt = $dbconc->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach ($cats as $cat){
                                    echo "<option value='". $cat['catID']."'>".$cat['catName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End categories Field -->

                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add item" class="btn btn-primary btn-sm"/>
                    </div>
                </div>
                <!-- End Submit Field -->
            </form>
        </div>
        <?php

    } elseif ($do == 'Insert') {
        //Start Insert page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Add Item</h1>";
            echo "<div class='container'>";
            $Name           = $_POST['Name'];
            $description    = $_POST['description'];
            $price          = $_POST['price'];
            $country        = $_POST['country'];
            $status         = $_POST['status'];
            $members        = $_POST['members'];
            $categories     = $_POST['categories'];
            // Validate the form
            $formErrors = array();
            if (empty($Name)) {
                $formErrors[] = 'Username can\'t be <strong>empty</strong>';
            }
            if (empty($description)) {
                $formErrors[] = 'Description can\'t be <strong>empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'Price can\'t be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'Country can\'t be <strong>empty</strong>';
            }
            if ($status === 0) {
                $formErrors[] = 'You must choose the <strong>Status</strong>';
            }
            if ($members === 0) {
                $formErrors[] = 'You must choose the <strong>member</strong>';
            }
            if ($categories === 0) {
                $formErrors[] = 'You must choose the <strong>categorie</strong>';
            }
            foreach ($formErrors as $error) {
                echo  '<div class="alert alert-danger">' . $error . '</div>';
            }
            if (empty($formErrors)) {
                    $stmt = $dbconc->prepare("INSERT INTO 
                                            items(Name, Description, Price, AddedDate,Country, status, Cat_ID, Member_ID)
                                            VALUES(:name, :des, :price, now(), :country, :status, :cat_id, :member_id)");
                    $stmt->execute(array(
                        'name'          => $Name,
                        'des'           => $description,
                        'price'         => $price,
                        'country'       => $country,
                        'status'        => $status,
                        'cat_id'        => $categories,
                        'member_id'     => $members,
                    ));
                    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted </div>';
                    redirectHome($msg, 'back');

            }
        } else {
            echo "<div class ='container'>";
            $msg = "<div class ='alert alert-danger'>You are not authorized to view this page.</div>";
            redirectHome($msg);
            echo "</div>";
        }
        echo "</div>";

    } elseif ($do == 'Edit') {
        // Check if id is numeric and get it's integer val
        $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;
        $stmt = $dbconc->prepare("SELECT * FROM items WHERE itemID = ?");
        $stmt->execute(array($itemID));
        $item = $stmt->fetch(); //Return data as Array
        $count = $stmt->rowCount();
        // Check if the id exists in DB
        if ($count > 0) { ?>
            <h1 class="text-center">Edit Items</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemID" value="<?php echo $itemID; ?>">
                    <!-- Start Item name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text"
                                   name="Name"
                                   class="form-control"
                                   required="required"
                                   placeholder="<?php echo $item['Name'] ?>"/>
                        </div>
                    </div>
                    <!-- End Item name Field -->
                    <!-- Start Item description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description </label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text"
                                   name="description"
                                   class="form-control"
                                   required="required"
                                   placeholder="<?php echo $item['Description'] ?>"/>
                        </div>
                    </div>
                    <!-- End Item description Field -->
                    <!-- Start Item price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text"
                                   name="price"
                                   class="form-control"
                                   required="required"
                                   placeholder="<?php echo $item['Price'] ?>"/>
                        </div>
                    </div>
                    <!-- End Item price Field -->
                    <!-- Start Item country Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text"
                                   name="country"
                                   class="form-control"
                                   required="required"
                                   placeholder="<?php echo $item['Country'] ?>"/>
                        </div>
                    </div>
                    <!-- End Item country Field -->
                    <!-- Start Item status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select name = "status">
                                <option value="1" <?php if ($item['status'] == 1) {echo 'selected';}?>>New</option>
                                <option value="2" <?php if ($item['status'] == 2) {echo 'selected';}?>>Semi-New</option>
                                <option value="3" <?php if ($item['status'] == 3) {echo 'selected';}?>>Used</option>
                                <option value="4" <?php if ($item['status'] == 4) {echo 'selected';}?>>Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Item status Field -->

                    <!-- Start Members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Members</label>
                        <div class="col-sm-10 col-md-6">
                            <select name = "members">
                                <?php
                                $stmt = $dbconc->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user){
                                    echo "<option value='" . $user['userID'] . "'" . ($item['Member_ID'] == $user['userID'] ? ' selected' : '') . ">" . $user['userName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
                    <!-- Start categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Categories</label>
                        <div class="col-sm-10 col-md-6">
                            <select name = "categories">
                                <?php
                                $stmt = $dbconc->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach ($cats as $cat){
                                    echo "<option value='" . $cat['catID'] . "'" . ($item['Cat_ID'] == $cat['catID'] ? ' selected' : '') . ">" . $cat['catName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End categories Field -->

                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Edit item" class="btn btn-primary btn-sm"/>
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
            <?php
            $stmt = $dbconc->prepare("SELECT comments.* , users.userName AS userName FROM comments
                                                INNER JOIN users
                                                ON users.userID = comments.user_id
                                                where item_id = ?");
            $stmt->execute(array($itemID));
            $rows = $stmt->fetchAll();
            if (!empty($rows)) {
            ?>
            <h1 class="text-center">Manage [<?php echo $item['Name'] ?>] comments</h1>
            <div class="  table-responsive ">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>Comment</td>
                        <td>User name</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['comment'] . "</td>";
                        echo "<td>" . $row['userName'] . "</td>";
                        echo "<td>" . $row['comment_date'] ."</td>";
                        echo "<td>
                            <a href='comments.php?do=Edit&comID=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='comments.php?do=Delete&comID=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                        if ($row['status'] == 0){
                            echo "<a href='comments.php?do=Approve&comID=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                        }
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>


        <?php }
            } else {
            echo "<div class ='container'>";
            $msg = "<div class ='alert alert-danger'>There is no such ID.</div>";
            redirectHome($msg);
            echo "</div>";
        }

    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";
            $id             = $_POST['itemID'];
            $Name           = $_POST['Name'];
            $description    = $_POST['description'];
            $price          = $_POST['price'];
            $country        = $_POST['country'];
            $status         = $_POST['status'];
            $members        = $_POST['members'];
            $categories     = $_POST['categories'];
            // Validate the form
            $formErrors = array();
            if (empty($Name)) {
                $formErrors[] = 'Username can\'t be <strong>empty</strong>';
            }
            if (empty($description)) {
                $formErrors[] = 'Description can\'t be <strong>empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'Price can\'t be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'Country can\'t be <strong>empty</strong>';
            }
            if ($status === 0) {
                $formErrors[] = 'You must choose the <strong>Status</strong>';
            }
            if ($members === 0) {
                $formErrors[] = 'You must choose the <strong>member</strong>';
            }
            if ($categories === 0) {
                $formErrors[] = 'You must choose the <strong>categorie</strong>';
            }
            foreach ($formErrors as $error) {
                echo  '<div class="alert alert-danger">' . $error . '</div>';
            }
            if (empty($formErrors)) {
                $stmt = $dbconc->prepare("UPDATE items SET 
                 Name = ? , 
                 Description = ? , 
                 Price = ? , 
                 Country= ? , 
                 status = ?,
                 Cat_ID = ?,
                 Member_ID = ?
                 WHERE itemID = ?");
                $stmt->execute(array($Name, $description, $price, $country,$status, $categories, $members , $id));
                $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record update </div>';
                redirectHome($msg, 'back');
            }
        } else {
            echo "<div class ='container'>";
            $msg= "<div class ='alert alert-danger'>You are not authorized to view this page.</div>";
            redirectHome($msg , 'back');
            echo "</div>";
        }
        echo "</div>";

    } elseif ($do == 'Delete') {
        echo '<h1 class="text-center">Delete Item</h1>';
        echo    '<div class="container">';
        // Check if id is numeric and get it's integer val
        $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;
        $check = checkItem('itemID', 'items', $itemID);
        // Check if the id exists in DB
        if ($check > 0) {
            $stmt = $dbconc->prepare("DELETE FROM items WHERE itemID = :zitemID Limit 1;");
            $stmt->bindParam(":zitemID", $itemID);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted </div>';
            redirectHome($msg , 'back');
        }else{
            $msg= "<div class ='alert alert-danger'>Item doesn\'t exist</div>";
            redirectHome($msg );
        }

    } elseif ($do == 'Approve') {
        echo '<h1 class="text-center">Approve Item</h1>';
        echo '<div class="container">';
        // Check if id is numeric and get it's integer val
        $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;
        $check = checkItem('itemID', 'items', $itemID);
        // Check if the id exists in DB
        if ($check > 0) {
            $stmt = $dbconc->prepare("UPDATE items SET Approve = 1 WHERE itemID = ? Limit 1;");
            $stmt->execute(array($itemID));
            $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved </div>';
            redirectHome($msg , 'back');
        }else{
            $msg= "<div class ='alert alert-danger'>Item doesn\'t exist</div>";
            redirectHome($msg );
        }
        echo '</div>';

    }

    include $tpl . 'footer.php';

} else {

    header('Location:index.php');

    exit();
}

ob_end_flush(); // Release The Output

