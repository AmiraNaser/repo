<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';

# Fetch Data ... 
$sql = "select subCategory.* , category.name from subCategory 
        join category on subCategory.category_id = category.id ";
$subop  = mysqli_query($con, $sql);


####################################################################################################

require '../layouts/header.php';
require '../layouts/sidenav.php';

require '../layouts/nav.php';
?>


<main>
    <div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <?php

           
              displayMessages('Dashboard/Display Sub Category');

            ?>
        </ol>


        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                DataTable Example
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>

                        <tbody>

                            <?php

                            while ($data = mysqli_fetch_assoc($subop)) {

                            ?>
                                <tr>
                                    <td><?php echo $data['id']; ?></td>
                                    <td><?php echo $data['title']; ?></td>
                                    <td><?php   echo $data['content']; ?></td>
                                    <td> <img src="./uploads/<?php echo $data['image']; ?>" height="100" width="100"> </td>
                                    <td><?php echo $data['name']; ?></td>

                                    <td>
                                        <a href='delete.php?id=<?php echo $data['id'];  ?>' class='btn btn-danger m-r-1em'>Delete</a>
                                        <a href='edit.php?id=<?php echo $data['id'];  ?>' class='btn btn-primary m-r-1em'>Edit</a>

                                    </td>

                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>


<?php

require '../layouts/footer.php';

?>