<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';

#############################################################################################

$id = $_GET['id'];
$sql = "select * from subcategory where id = $id";
$op = mysqli_query($con, $sql);
$subcatData = mysqli_fetch_assoc($op);

##############################################################################################

$sql = "select * from category";
$cate_op  = mysqli_query($con, $sql);


#############################################################################################


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // CODE ..... 

    $title   = Clean($_POST['title']);
    $content = Clean($_POST['content']);
    $cat_id  = Clean($_POST['cat_id']);


    # Validate Input ... 

    $errors = [];
    # Validate title ... 
    if (!validate($title, 1)) {
        $errors['title'] = " Title Required";
    }


    # Validate Email .... 
    if (!validate($content, 1)) {
        $errors['content'] = " Content Required";
    }

    # Validate cat_id  ... 
    if (!validate($cat_id, 1)) {
        $errors['Category'] = " Category Required";
    } elseif (!validate($cat_id, 4)) {
        $errors['Category'] = " Category Invalid";
    }


    #Validate Image ... 
    if (!validate($_FILES['image']['name'], 1)) {
        $errors['Image']  = "Image Required";
    } elseif (!validate($_FILES['image']['name'], 5)) {
        $errors['Image']  = "Image : Invalid Extension";
    }



    # Check Errors 
    if (count($errors) > 0) {
        $_SESSION['Message'] = $errors;
    } else {
        # logic .... 

        if (validate($_FILES['image']['name'], 1)) {

            $image = uploadFile($_FILES);

            if (!empty($image)) {
                unlink('./uploads/' . $subcatData['image']);
            }
        } else {
            $image = $subcatData['image'];
        }




        $sql = "update subcategory set title =  '$title' , content = '$content' , image = '$image' ,category_id = $cat_id  where id = $id";
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



#############################################################################################

require '../layouts/header.php';
require '../layouts/sidenav.php';

require '../layouts/nav.php';
?>




<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">



            <?php

            displayMessages('Dashboard/Edit Sub Category');
            ?>



        </ol>



        <div class="container">


            <form action="edit.php?id=<?php echo  $subcatData['id']; ?>" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="exampleInputName">Title</label>
                    <input type="text" class="form-control" id="exampleInputName" aria-describedby="" name="title" value="<?php echo $subcatData['title'] ?>">
                </div>



                <div class="form-group">
                    <label for="exampleInputEmail">Content</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="content" value="<?php echo $subcatData['content'] ?>">
                </div>

                <!-- <div class="form-group">
                    <label for="exampleInputPassword">New Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
                </div> -->




                <div class="form-group">
                    <label for="exampleInputPassword">Category</label>
                    <select class="form-control" name="cat_id">

                        <?php
                        while ($cat_data = mysqli_fetch_assoc($cate_op)) {
                        ?>

                            <option  value="<?php echo $cat_data['id']; ?>"  <?php if ($subcatData['category_id'] == $cat_data['id']) {echo 'selected';} ?>> <?php echo $cat_data['name']; ?></option>

                        <?php }  ?>

                    </select>
                </div>





                <div class="form-group">
                    <label for="exampleInputPassword">Image</label>
                    <input type="file" name="image">
                </div>
                <br>
                <img src="./uploads/<?php echo $subcatData['image']; ?>" height="100" width="100" alt="">
                <br>

                <button type="submit" class="btn btn-primary">Edit</button>
            </form>
        </div>




    </div>
</main>


<?php

require '../layouts/footer.php';

?>