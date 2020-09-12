<?php
include 'conn.php';
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
            <?php
            if(isset($_GET["did"])){
                $id=intval($_GET["did"]);
                $query="select * from tbl_students where id='$id'";
                $result=mysqli_query($link,$query);
                if($result){
                    $data=mysqli_fetch_array($result);
                    ?>

                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" value="<?= $data["student_name"] ?>" id="name" placeholder="Enter name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control" value="<?= $data["email"] ?>"  id="email" placeholder="Enter email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile:</label>
                            <input type="text" class="form-control" value="<?= $data["mobile"] ?>"  id="mobile" placeholder="Enter mobile" name="mobile">
                        </div>

                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" class="form-control"  id="image"  name="image">
                            <input type="hidden" name="old_image" value="<?= $data["image"] ?>" >
                        </div>

                        <button type="submit" name="btnSave" class="btn btn-default">Update</button>
                    </form>

                    <?php
                    if(isset($_POST["btnSave"])){
                        $name = mysqli_real_escape_string($link,$_POST["name"]);
                        $email = mysqli_real_escape_string($link,$_POST["email"]);
                        $mobile = mysqli_real_escape_string($link,$_POST["mobile"]);
                        $old_image = mysqli_real_escape_string($link,$_POST["old_image"]);

                        if(empty($_FILES["image"]["tmp_name"]) || !is_uploaded_file($_FILES["image"]["tmp_name"])){
                            $randomName = $old_image;
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

                           $query = "update tbl_students set student_name='$name',email='$email',mobile='$mobile',image='$randomName' where id='$id'";
                            $result = mysqli_query($link,$query);
                            if($result){
                                ?>
                                <div class="alert alert-success">
                                    <strong>Success!</strong> Record Updated Successfully...
                                </div>
                                <?php
                                header("refresh: 2;url = insert.php");

                            }else{
                                ?>
                                <div class="alert alert-danger">
                                    <strong>Error!</strong> While Process.
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>


                    <?php
                }
            }
            ?>






        </div>
    </div>

</div>

</body>
</html>
