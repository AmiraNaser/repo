<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';

$sql = "select * from product";
$pro_op  = mysqli_query($con,$sql);


$id = $_GET['id']; 

$sql = "select * from discount where id = $id"; 
$op  = mysqli_query($con,$sql);
$disData = mysqli_fetch_assoc($op);

 if($_SERVER['REQUEST_METHOD'] == "POST"){

    $percent        = Clean($_POST['percent']);
    $description    = Clean($_POST['description']);
    $pro_id         = Clean($_POST['pro_id']);
    $selected_radio = Clean($_POST['active']);

    $errors = [];

    if(!Validate($description)){
        $errors['Description'] = " Description Required"; 
    }
    if(!Validate($percent)){
        $errors['Price'] = " Price Required"; 
    } elseif (!Validate($percent, 2)) {
        $errors['Price'] = " Invalid Price";
    }
    if (!Validate($pro_id)) {
        $errors['Product'] = " Product Required";
    } elseif (!Validate($pro_id, 5)) {
        $errors['Product'] = " Product Invalid";
    }
    if(!Validate($selected_radio)){
        $errors['selected_radio'] = " selected_radio Required"; 
    }    


   if (count($errors) > 0) {
    $_SESSION['Message'] = $errors;
} else {

    $sql = "update discount  set discountPercent =  $percent ,description = '$description', product_id = '$pro_id',active = '$selected_radio' where id = $id";
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


            <form action="edit.php?id=<?php echo  $disData['id']; ?>" method="post" enctype="multipart/form-data">

            <div class="form-group">
                    <label for="exampleInputPro">Product</label>
                    <select class="form-control"  id="exampleInputPro"  name="pro_id" >
                        <?php
                        while($pro_data = mysqli_fetch_assoc($pro_op)) {
                        ?>
                        <option value="<?php echo $pro_data['id'];?>" <?php if ($pro_data['id'] == $disData['product_id']) { echo 'selected'; }?> > <?php echo $pro_data['title'];?></option>    
                        <?php } ?>
                        
                    </select>    
                </div>                
                <div class="form-group">
                    <label for="exampleInputNum">Percent</label>
                    <input type="number" step="0.01" class="form-control"  id="exampleInputNum" aria-describedby="" name="percent" value = "<?php echo $disData['discountPercent'];?>" placeholder="">
                </div>              
                <div class="form-group">
                    <label for="exampleInputDesc">Description</label>
                    <input type="text" class="form-control"  id="exampleInputDesc" aria-describedby="" name="description" value = "<?php echo $disData['description'];?>" placeholder="">
                </div>
                <div class="form-group">
                
                    <label for="activeChoice1">Active</label>
                    <input type="radio" id="activeChoice1" name="active" <?php if (isset($selected_radio) && $selected_radio== "Active") echo "checked";?> value="Active">
                    <label for="activeChoice2">Not Active</label>
                    <input type="radio" id="activeChoice2" name="active" <?php if (isset($selected_radio) && $selected_radio== "Inactive") echo "checked";?> value="Inactive">                    
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>




    </div>
</main>


<?php

require '../layouts/footer.php';

?>