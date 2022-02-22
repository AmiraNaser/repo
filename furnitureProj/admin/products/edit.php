<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';

$sql = "select * from subCategory";
$subCat_op  = mysqli_query($con,$sql);

// $dis_sql = "select * from discount";
// $dis_op  = mysqli_query($con,$dis_sql);

$id = $_GET['id']; 

$sql = "select * from product where id = $id"; 
$op  = mysqli_query($con,$sql);
$proData = mysqli_fetch_assoc($op);

 if($_SERVER['REQUEST_METHOD'] == "POST"){

    $title       = Clean($_POST['title']);
    $description = Clean($_POST['description']);
    $price       = Clean($_POST['price'], 1);
    $subCat_id   = Clean($_POST['subCat_id']);
    //$discount_id = Clean($_POST['discount_id']);

    $errors = [];

    if(!Validate($title)){
        $errors['Title'] = " Title Required"; 
    }
    if(!Validate($description)){
        $errors['Description'] = " Description Required"; 
    }
    if(!Validate($price)){
        $errors['Price'] = " Price Required"; 
    } elseif (Validate($price, 2)) {
        $errors['Price'] = " Invalid Price";
    }
    // if (!Validate($discount_id)) {
    //     $errors['Discount'] = " Discount Required";
    // } elseif (!Validate($discount_id, 5)) {
    //     $errors['Discount'] = " Discount Invalid";
    // }
    if (!Validate($subCat_id)) {
        $errors['Sub-Category'] = " Sub-Category Required";
    } elseif (!Validate($subCat_id, 5)) {
        $errors['Sub-Category'] = " Sub-Category Invalid";
    }     
    if (Validate($_FILES['image']['name'])) {

        if (!Validate($_FILES['image']['name'], 4)) {
            $errors['Image']  = "Image : Invalid Extension";
        }
    }

   if (count($errors) > 0) {
    $_SESSION['Message'] = $errors;
} else {
    if (validate($_FILES['image']['name'])) {

        $image = uploadFile($_FILES);

        if (!empty($image)) {
            unlink('./uploads/' . $proData['image']);
        }
    } else {
        $image = $proData['image'];
    }
    $sql = "update product  set title =  '$title' ,price = '$price', description = '$description',sub_category_id = '$subCat_id', image = '$image' where id = $id";
    $op  = mysqli_query($con, $sql);

    if ($op) {
        $message = ["Raw Updated"];
        $_SESSION['Message'] = $message;

        header("Location: index.php");
        exit();
    } else {
        $message = ["Error Try Again"];
        $_SESSION['Message'] = $message;
    }
   }

 } 

require '../layouts/header.php';
require '../layouts/nav.php';
require '../layouts/sidenav.php';
?>




<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>

        <ol class="breadcrumb mb-4">         
            <?php 
              
               displayMessages('Dashboard/Edit Category');
            ?>
        </ol>
        <div class="container">


            <form action="edit.php?id=<?php echo  $proData['id']; ?>" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="exampleInputTitle">Title</label>
                    <input type="text" class="form-control"  id="exampleInputTitle" aria-describedby="" name="title" value = "<?php echo $proData['title'];?>" placeholder="Enter Product Title">
                </div>
                <div class="form-group">
                    <label for="exampleInputNum">Price</label>
                    <input type="number" class="form-control"  id="exampleInputNum" aria-describedby="" name="price" value = "<?php echo $proData['price'];?>" placeholder="Enter Price">
                </div>
                <div class="form-group">
                    <label for="exampleInputDesc">Description</label>
                    <input type="text" class="form-control"  id="exampleInputDesc" aria-describedby="" name="description" value = "<?php echo $proData['description'];?>" placeholder="Enter Description">
                </div>

                <div class="form-group">
                    <label for="exampleInputSubCat">Sub-Category</label>
                    <select class="form-control"  id="exampleInputSubCat"  name="subCat_id" >
                        <?php
                        while($subCat_data = mysqli_fetch_assoc($subCat_op)) {
                        ?>
                        <option value="<?php echo $subCat_data['id'];?>" <?php if ($subCat_data['id'] == $proData['sub_category_id']) { echo 'selected'; }?> > <?php echo $subCat_data['title'];?> </option>    
                        <?php } ?>
                        
                    </select>    
                </div>
                <!-- <div class="form-group">
                    <label for="exampleInputDisId">Discount</label>
                    <select class="form-control"  id="exampleInputDisId"  name="discount_id" >
                        <?php
                        while($dis_data = mysqli_fetch_assoc($dis_op)) {
                        ?>
                        <option value="<?php echo $dis_data['id'];?>" <?php if ($dis_data['id'] == $proData['discount_id']) { echo 'selected'; }?> > <?php echo $dis_data['discountPercent'];?> </option>    
                        <?php } ?>
                        
                    </select>    
                </div>   -->
                <div class="form-group">
                    <label for="exampleInputPassword">Image</label>
                    <input type="file" name="image">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>




    </div>
</main>


<?php

require '../layouts/footer.php';

?>