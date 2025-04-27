<?php 
session_start();
include 'components/connect.php';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $c_pass = sha1($_POST['c_pass']);
    
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = 'users_' . create_unique_id() . '.' . $ext; 
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_files/' . $rename;
    

    $select_user_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user_email->execute([$email]);

    if ($select_user_email->rowCount() > 0) {
        $message[] = 'Email already taken!';
    } else {
        if ($pass != $c_pass) {
            $message[] = 'Passwords do not match!';
        } else {
            if ($image_size > 2000000) {
                $message[] = 'Image size too large!';
            } else {
                $insert_user = $conn->prepare("INSERT INTO `users` (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
                $insert_user->execute([$id, $name, $email, $c_pass, $rename]);
                move_uploaded_file($image_tmp_name, $image_folder);

                $verify_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
                $verify_user->execute([$id]);
                $row = $verify_user->fetch(PDO::FETCH_ASSOC);

                if ($insert_user) {
                    if ($verify_user->rowCount() > 0) {
                        $_SESSION['user_id'] = $row['id']; 
                        header('location:home.php');
                        exit();
                    } else {
                        $message[] = 'Something went wrong!';
                    }
                }
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>


<section class="form-container">

<form action="" method="post" enctype="multipart/form-data">
    <h3>register new</h3>
<div class="flex">
    <div class="col">
    <p>your name <span>*</span></p>
    <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="box">
    <p>your email</p>
    <input type="email" name="email" maxlength="50" required placeholder="enter your email" class="box">
    </div>

    <div class="col">
    <p>your password</p>
    <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box">
    <p>confirm password</p>
    <input type="password" name="c_pass" maxlength="20" required placeholder="confirm your password" class="box">
    </div>
</div>
<p>select pic <span>*</span></p>
<input type="file" name="image" class="box" required accept="image/*">
<input type="submit" value="register now" name="submit" class="btn">
</form>

</section>
























<script src="js/script.js"></script>

</body>
</html>