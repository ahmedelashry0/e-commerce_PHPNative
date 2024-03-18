<?php
ob_start();
session_start();
$pageTitle = "Create New Item";
include 'init.php';
if (isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErrors = array();
        $itemName       = filter_var($_POST['name'], FILTER_UNSAFE_RAW);
        $itemDesc       = filter_var($_POST['description'], FILTER_UNSAFE_RAW);
        $itemPrice      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $itemCountry    = filter_var($_POST['country'], FILTER_UNSAFE_RAW);
        $itemStatus     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $itemCat        = filter_var($_POST['categories'], FILTER_SANITIZE_NUMBER_INT);
        $tags 		    = filter_var($_POST['tags'], FILTER_UNSAFE_RAW);

        if (strlen($itemName) < 4) {

            $formErrors[] = 'Item Title Must Be At Least 4 Characters';

        }

        if (strlen($itemDesc) < 10) {

            $formErrors[] = 'Item Description Must Be At Least 10 Characters';

        }

        if (strlen($itemCountry) < 2) {

            $formErrors[] = 'Item Title Must Be At Least 2 Characters';

        }

        if (empty($itemPrice)) {

            $formErrors[] = 'Item Price Cant Be Empty';

        }

        if (empty($itemStatus)) {

            $formErrors[] = 'Item Status Cant Be Empty';

        }

        if (empty($itemCountry)) {

            $formErrors[] = 'Item Category Cant Be Empty';

        }
        if (empty($formErrors)){
            $stmt = $dbconc->prepare("INSERT INTO
                                            items(Name, Description, Price, AddedDate,Country, status, Cat_ID, Member_ID, tags)
                                            VALUES(:name, :des, :price, now(), :country, :status, :cat_id, :member_id, :tags)");
            $stmt->execute(array(
                'name'          => $itemName,
                'des'           => $itemDesc,
                'price'         => $itemPrice,
                'country'       => $itemCountry,
                'status'        => $itemStatus,
                'cat_id'        => $itemCat,
                'member_id'     => $_SESSION['uid'],
                'tags'          => $tags,
            ));
            // Echo Success Message

            if ($stmt) {

                $succesMsg = 'Item Has Been Added';

            }
        }
    }

    ?>
    <h1 class="text-center"><?php echo $pageTitle ?></h1>
    <div class="create-ad block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $pageTitle ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal main-form" action="?do=<?php echo $_SERVER['PHP_SELF'] ?>"
                                  method="POST">
                                <!-- Start Item name Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                                pattern=".{4,}"
                                                title="This Field Require At Least 4 Characters"
                                                type="text"
                                                name="name"
                                                class="form-control live"
                                                placeholder="Name of The Item"
                                                data-class=".live-title"
                                                required/>
                                    </div>
                                </div>
                                <!-- End Item name Field -->
                                <!-- Start Item description Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Description </label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                                pattern=".{10,}"
                                                title="This Field Require At Least 10 Characters"
                                                type="text"
                                                name="description"
                                                class="form-control live"
                                                placeholder="Description of The Item"
                                                data-class=".live-desc"
                                                required/>
                                    </div>

                                </div>
                                <!-- End Item description Field -->
                                <!-- Start Item price Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                                type="text"
                                                name="price"
                                                class="form-control live"
                                                data-class=".live-price"
                                                placeholder="price of the item"
                                                required/>
                                    </div>
                                </div>
                                <!-- End Item price Field -->
                                <!-- Start Item country Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                                type="text"
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
                                    <div class="col-sm-10 col-md-9">
                                        <select name="status">
                                            <option value="">...</option>
                                            <option value="1">New</option>
                                            <option value="2">Semi-New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Very Old</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Item status Field -->

                                <!-- Start categories Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Categories</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="categories">
                                            <option value="">...</option>
                                            <?php
                                            $cats = getAllFrom('*' ,'categories', '', '', 'CatID');
                                            foreach ($cats as $cat) {
                                                echo "<option value='" . $cat['catID'] . "'>" . $cat['catName'] . "</option>";
                                                    $childCats = getAllFrom("*", "categories", "where parent = {$cat['catID']}", "", "catID");
                                                    foreach ($childCats as $child) {
                                                        echo "<option value='" . $child['catID'] . "'>--- " . $child['catName'] . "</option>";
                                                    }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- End categories Field -->

                                <!-- Start Tags Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                                type="text"
                                                name="tags"
                                                class="form-control"
                                                placeholder="Separate Tags With Comma (,)" />
                                    </div>
                                </div>
                                <!-- End Tags Field -->

                                <!-- Start Submit Field -->
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <input type="submit" value="Add item" class="btn btn-primary btn-sm"/>
                                    </div>
                                </div>
                                <!-- End Submit Field -->
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
							<span class="price-tag">
								$<span class="live-price">0</span>
							</span>
                                <img class="img-responsive" src="img.png" alt=""/>
                                <div class="caption">
                                    <h3 class="live-title">Title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start Loopiong Through Errors -->
                    <?php
                    if (!empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                    if (isset($succesMsg)) {
                        echo '<div class="alert alert-success">' . $succesMsg . '</div>';
                    }
                    ?>
                    <!-- End Loopiong Through Errors -->
                </div>
            </div>
        </div>
    </div>

    <?php
} else {
    header("location:login.php");
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>