<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';

#############################################################################################

$sql = "select * from category";
$catOp  = mysqli_query($con, $sql);

#############################################################################################


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // CODE ..... 

    $title   = Clean($_POST['title']);
    $content = Clean($_POST['content']);
    $cat_id  = Clean($_POST['cat_id']);


    # Validate Input ... 

    $errors = [];
    # Validate title ... 
    if (!validate($title)) {
        $errors['title'] = " Title Required";
    }


    # Validate Email .... 
    if (!validate($content)) {
        $errors['content'] = " Content Required";
    }

    # Validate Role_id  ... 
    if (!validate($cat_id)) {
        $errors['Category'] = " Category Required";
    } elseif (!validate($cat_id, 5)) {
        $errors['Category'] = " Category Invalid";
    }


    #Validate Image ... 
    if (!validate($_FILES['image']['name'])) {
        $errors['Image']  = "Image Required";
    } elseif (!validate($_FILES['image']['name'], 4)) {
        $errors['Image']  = "Image : Invalid Extension";
    }


    # Check Errors 
    if (count($errors) > 0) {
        $_SESSION['Message'] = $errors;
    } else {
        # logic .... 

        $image = uploadFile($_FILES);

        if (empty($image)) {
            $_SESSION['Message'] = ["Error In Uploading File Try Again"];
        } else {


            $sql = "insert into subCategory (title,content,image,category_id) values ('$title' , '$content','$image',$cat_id)";
            $op  = mysqli_query($con, $sql);

            if ($op) {
                $message = ["Raw Inserted"];
            } else {
                $message = ["Error Try Again"];
            }

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

            displayMessages('Dashboard/Sub Category');

            ?>



        </ol>



        <div class="container">


            <form action="<?php echo  htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="exampleInputName">Title</label>
                    <input type="text" class="form-control" id="exampleInputName" aria-describedby="" name="title" placeholder="Enter Title">
                </div>



                <div class="form-group">
                    <label for="exampleInputEmail">Content</label>
                    <input type="text" class="form-control" id="exampleInputName" aria-describedby="" name="content" placeholder="Enter content">
                </div>


                <div class="form-group">
                    <label for="exampleInputPassword">Category</label>
                    <select class="form-control" name="cat_id">

                        <?php
                        while ($Cat_data = mysqli_fetch_assoc($catOp)) {
                        ?>

                            <option value="<?php echo $Cat_data['id']; ?>"><?php echo $Cat_data['name']; ?></option>

                        <?php }  ?>

                    </select>
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

require "../layouts/footer.php";

?>