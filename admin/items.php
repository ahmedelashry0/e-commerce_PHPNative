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


    } elseif ($do == 'Add') { ?>
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


    } elseif ($do == 'Update') {


    } elseif ($do == 'Delete') {


    } elseif ($do == 'Approve') {


    }

    include $tpl . 'footer.php';

} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

