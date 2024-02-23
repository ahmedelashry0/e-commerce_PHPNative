<?php
$pageTitle = 'Categories';
ob_start();
session_start();

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {
//        $sort = 'asc';
        $sort_array = array('asc', 'desc');
        $sort = isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array) ? $sort = $_GET['sort'] : 'asc';
        $stmt = $dbconc->prepare("SELECT * FROM categories ORDER BY ordering $sort");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        if (! empty($cats)) {

            ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-edit"></i> Manage Categories
                        <div class="option pull-right">
                            <i class="fa fa-sort"></i> Ordering: [
                            <a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">Asc</a> |
                            <a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">Desc</a> ]
                            <i class="fa fa-eye"></i> View: [
                            <span class="active" data-view="full">Full</span> |
                            <span data-view="classic">Classic</span> ]
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        foreach($cats as $cat) {
                            echo "<div class='cat'>";
                            echo "<div class='hidden-buttons'>";
                            echo "<a href='categories.php?do=Edit&catid=" . $cat['catID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                            echo "<a href='categories.php?do=Delete&catid=" . $cat['catID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                            echo "</div>";
                            echo "<h3>" . $cat['catName'] . '</h3>';
                            echo "<div class='full-view'>";
                            echo "<p>"; if($cat['catDescription'] == '') { echo 'This category has no description'; } else { echo $cat['catDescription']; } echo "</p>";
                            if($cat['visibility'] == 1) { echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> Hidden</span>'; }
                            if($cat['Allow_comments'] == 1) { echo '<span class="commenting cat-span"><i class="fa fa-close"></i> Comment Disabled</span>'; }
                            if($cat['Allow_Ads'] == 1) { echo '<span class="advertises cat-span"><i class="fa fa-close"></i> Ads Disabled</span>'; }
                            echo "</div>";

                            // Get Child Categories
//                            $childCats = getAllFrom("*", "categories", "where parent = {$cat['catID']}", "", "ID", "ASC");
//                            if (! empty($childCats)) {
//                                echo "<h4 class='child-head'>Child Categories</h4>";
//                                echo "<ul class='list-unstyled child-cats'>";
//                                foreach ($childCats as $c) {
//                                    echo "<li class='child-link'>
//												<a href='categories.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a>
//												<a href='categories.php?do=Delete&catid=" . $c['ID'] . "' class='show-delete confirm'> Delete</a>
//											</li>";
//                                }
//                                echo "</ul>";
//                            }

                            echo "</div>";
                            echo "<hr>";
                        }
                        ?>
                    </div>
                </div>
                <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
            </div>

        <?php } else {

            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Categories To Show</div>';
            echo '<a href="categories.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Category
						</a>';
            echo '</div>';

        } ?>

        <?php
    } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Categories</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Categorie Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="catName" class="form-control" autocomplete="off" required="required" placeholder="Name of the categorie" />
                    </div>
                </div>
                <!-- End Username Field -->
                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class=" form-control" placeholder="Describe the categorie" />
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start Ordering Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Arrange</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="number" name="ordering" class="form-control" placeholder="Number to arrange the categorie" />
                    </div>
                </div>
                <!-- End Ordering Field -->
                <!-- Start Visible Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1">
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Visible Field -->
                <!-- Start Commenting Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" checked>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1">
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Commenting Field -->
                <!-- Start Ads Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1">
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Ads Field -->
                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Categorie" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- End Submit Field -->
            </form>
        </div>
        <?php

    } elseif ($do == 'Insert') {
        //Start Insert page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Add Categorie</h1>";
            echo "<div class='container'>";
            $catName            = $_POST['catName'];
            $description        = $_POST['description'];
            $order              = $_POST['ordering'];
            $visibility         = $_POST['visibility'];
            $commenting         = $_POST['commenting'];
            $Ads                = $_POST['ads'];
            if (!empty($catName)) {
                // Check if the categorie is already exist in DB
                $check= checkItem("catName","categories",$catName);
                if ($check == 1){
                    $msg = "<div class ='alert alert-danger'>Categorie Name already exists</div>";
                    redirectHome($msg, 'back');
                }else{
                    $stmt = $dbconc->prepare("INSERT INTO 
                                            categories(catName, catDescription, ordering, visibility,Allow_comments, Allow_Ads)
                                            VALUES(:cat, :des, :order, :visibility, :com, :ads)");
                    $stmt->execute(array(
                        'cat'           => $catName,
                        'des'           => $description,
                        'order'         => empty($order) ? Null : $order,
                        'visibility'    => $visibility,
                        'com'           => $commenting,
                        'ads'           => $Ads
                    ));
                    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted </div>';
                    redirectHome($msg, 'back');
                }
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


    }
    include $tpl . 'footer.php';

} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output