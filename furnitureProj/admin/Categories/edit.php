<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';

$id = $_GET['id']; 

$sql = "select * from category where id = $id"; 
$op  = mysqli_query($con,$sql);
$catData = mysqli_fetch_assoc($op);

 if($_SERVER['REQUEST_METHOD'] == "POST"){

    $title = Clean($_POST['title']);
    
    $errors = [];

    if(!validate($title)){
        $errors['Title'] = " Title Required"; 
    }
    if (validate($_FILES['image']['name'])) {

        if (!validate($_FILES['image']['name'], 4)) {
            $errors['Image']  = "Image : Invalid Extension";
        }
    }

   if (count($errors) > 0) {
    $_SESSION['Message'] = $errors;
} else {
    if (validate($_FILES['image']['name'])) {

        $image = uploadFile($_FILES);

        if (!empty($image)) {
            unlink('./uploads/' . $catData['image']);
        }
    } else {
        $image = $catData['image'];
    }
    $sql = "update category  set name =  '$title' , image = '$image' where id = $id";
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


            <form action="edit.php?id=<?php echo  $catData['id']; ?>" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="exampleInputName">Title</label>
                    <input type="text" class="form-control"  id="exampleInputName" aria-describedby="" name="title" value = "<?php echo $catData['name'];?>" placeholder="Enter catTitle">
                </div>

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