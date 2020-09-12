<?php
include 'conn.php';

if(isset($_GET["did"])){
    $id = intval($_GET["did"]);
    $deleteQuery = "delete from tbl_students where id='$id'";
    $result = mysqli_query($link,$deleteQuery);
    header("location:insert.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP CRUD</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile:</label>
                    <input type="text" class="form-control" id="mobile" placeholder="Enter mobile" name="mobile">
                </div>

                <div class="form-group">
                    <label for="pic">Profile Picture:</label>
                    <input type="file" class="form-control" id="pic"  name="image">
                </div>

                <button type="submit" name="btnSave" class="btn btn-default">Submit</button>
            </form>

            <?php
            if(isset($_POST["btnSave"])){
                $name = mysqli_real_escape_string($link,$_POST["name"]);
                $email = mysqli_real_escape_string($link,$_POST["email"]);
                $mobile = mysqli_real_escape_string($link,$_POST["mobile"]);


                if(empty($_FILES["image"]["tmp_name"]) || !is_uploaded_file($_FILES["image"]["tmp_name"])){
                    $randomName = '';
                }else{
                    $filename = $_FILES["image"]["name"];
                    $imageType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

                    if($imageType != "jpg" && $imageType!= "png" && $imageType !="jpeg" && $imageType!= "gif"){
                        $errors[] = "Sorry,Only JPG,JPEG and GIF are allowed";
                    }else{
                        $randomName = rand(111111111,999999999).'.'.$imageType;
                        move_uploaded_file($_FILES["image"]["tmp_name"],"uploads/$randomName");
                    }

                }

                if(!empty($errors)){
                     foreach ($errors as $error){
                         echo $error;
                     }
                }else{

                    $query = "insert into tbl_students(student_name,email,mobile,image,addon) values ('$name','$email','$mobile','$randomName',now())";
                    $result = mysqli_query($link,$query);
                    if($result){
                        ?>
                        <div class="alert alert-success">
                            <strong>Success!</strong> Record Added Successfully...
                        </div>
                        <?php
                    }else{
                     //   echo mysqli_error($link);
                        ?>
                        <div class="alert alert-danger">
                            <strong>Error!</strong> While Process.
                        </div>
                        <?php
                    }
                }





            }
            ?>

        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Image</th>
                        <th>Addon</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $fetchQuery = "Select * from tbl_students order by id desc";
                    $result = mysqli_query($link,$fetchQuery);
                    if($result){
                        $sr=0;
                        while($data=mysqli_fetch_array($result)){
                            ?>
                            <tr>
                                <td><?= ++$sr ?></td>
                                <td><?= $data["student_name"]  ?></td>
                                <td><?= $data["email"]  ?></td>
                                <td><?= $data["mobile"]  ?></td>
                                <td><img src="uploads/<?= $data["image"]  ?>" style="width:150px;height: 150px;"></td>
                                <td><?= $data["addon"]  ?></td>
                                <td>
                                    <a onclick="return confirm('Do you really want to delete this')" href="insert.php?did=<?= $data["id"]; ?>" class="btn btn-danger">Delete</a>
                                    <a  href="update.php?did=<?= $data["id"]; ?>" class="btn btn-warning">Update</a>
                                </td>
                            </tr>
                            <?
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>
