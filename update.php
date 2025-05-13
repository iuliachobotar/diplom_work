<?php 
session_start();
include 'components/connect.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
    header('location:home.php');
}

if(isset($_POST['submit'])){

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
    $select_user->execute([$user_id]);
    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    
    if(!empty($name)){
        $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
        $update_name->execute([$name, $user_id]);
        $message[] = 'name updated succrssfully!';
    }

    if(!empty($email)){
        $select_user_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user_email->execute([$email]);
        if($select_user_email->rowCount() > 0){
            $message[] = 'email already taken!';
        }else{
        $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
        $update_email->execute([$email, $user_id]);
        $message[] = 'email updated succrssfully!';
        }

        
    }

    $prev_image = $fetch_user['image'];
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_files/' . $rename;

    if(!empty($image)){
        if($image_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
            $update_image->execute([$rename, $user_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            if($prev_image != '' AND $prev_image != $rename){
                unlink('uploaded_files/'.$prev_image);
            }
            $message[] = 'image updated succrssfully!';
        }
    }

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $fetch_user['password'];
    $new_pass = sha1($_POST['new_pass']);
    $old_pass = sha1($_POST['old_pass']);
    $c_pass = sha1($_POST['c_pass']);

    if($old_pass != $empty_pass){
        if($old_pass != $prev_pass){
            $message[] = 'old password not matched!';
        }elseif($new_pass != $c_pass){
            $message[] = 'confirm password not matched!';
        }else{
            if($new_pass != $empty_pass){
                $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
                $update_pass->execute([$c_pass, $user_id]);
                $message[] = 'password updated succrssfully!';
            }else{
                $message[] = 'please enter new password';
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
    <title>Update</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">

<form action="" method="post" enctype="multipart/form-data">
    <h3>релагувати профіль</h3>
<div class="flex">
    <div class="col">
    <p>твоє ім'я</p>
    <input type="text" name="name" maxlength="50" placeholder="<?= $fetch_profile['name']; ?>" class="box">
    <p>твій email</p>
    <input type="email" name="email" maxlength="50" placeholder="<?= $fetch_profile['email']; ?>" class="box">
    <p>обери фото</p>
    <input type="file" name="image" class="box"  accept="image/*">
    </div>

    <div class="col">
    <p>старий пароль</p>
    <input type="password" name="old_pass" maxlength="20" placeholder="напиши старий пароль" class="box">
    
    <p>новий пароль</p>
    <input type="password" name="new_pass" maxlength="20" placeholder="напиши новий пароль" class="box">
    <p>повтори новий пароль</p>
    <input type="password" name="c_pass" maxlength="20" placeholder="повтори новий пароль" class="box">
    
</div>
</div>
<input type="submit" value="оновити профіль" name="submit" class="btn" >
</form>

</section>
























<script src="js/script.js"></script>

</body>
</html>