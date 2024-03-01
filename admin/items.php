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

