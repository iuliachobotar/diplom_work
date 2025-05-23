<?php
session_start();
include '../components/connect.php';

if (isset($_SESSION['tutor_id'])) {
    $tutor_id = $_SESSION['tutor_id'];
} else {
    $tutor_id = '';
}

if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $c_pass = sha1($_POST['c_pass']);
    
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext; 
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = '../uploaded_files/' . $rename;
    

    $select_tutor_email = $conn->prepare("SELECT * FROM `tutor` WHERE email = ?");
    $select_tutor_email->execute([$email]);

    if ($select_tutor_email->rowCount() > 0) {
        $message[] = 'Email already taken!';
    } else {
        if ($pass != $c_pass) {
            $message[] = 'Passwords do not match!';
        } else {
            if ($image_size > 2000000) {
                $message[] = 'Image size too large!';
            } else {
                $insert_tutor = $conn->prepare("INSERT INTO `tutor` (id, name, profession, email, password, image) VALUES (?, ?, ?, ?, ?, ?)");
                $insert_tutor->execute([$id, $name, $profession, $email, $c_pass, $rename]);
                move_uploaded_file($image_tmp_name, $image_folder);

                $verify_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE email = ? AND password = ? LIMIT 1");
                $verify_tutor->execute([$email, $c_pass]);
                $row = $verify_tutor->fetch(PDO::FETCH_ASSOC);

                if ($insert_tutor) {
                    if ($verify_tutor->rowCount() > 0) {
                        $_SESSION['tutor_id'] = $row['id']; 
                        header('location: dashboard.php');
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

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body style="padding-left: 0;">


<?php
if(isset($message)){
    foreach($message as $message){
        echo '
        <div class="message form">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>


<section class="form-container">

<form action="" method="post" enctype="multipart/form-data">
    <h3>реєстрація</h3>
<div class="flex">
    <div class="col">
    <p>твоє ім'я <span>*</span></p>
    <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="box">
    <p>твоя професія <span>*</span></p>
    <select name="profession" class="box">
        <option value="" disabled selected>-- /оберіть професію</option>
        <option value="developer">розробник</option>
        <option value="designer">дизайнер</option>
        <option value="musician">музикант</option>
        <option value="biologist">біолог</option>
        <option value="artist">художник</option>
        <option value="teacher">вчитель</option>
        <option value="engineer">інженер</option>
        <option value="lawyer">адвокат</option>
        <option value="accountant">бухгалтер</option>
        <option value="doctor">лікар</option>
        <option value="journalist">журналіст</option>
        <option value="photographer">фотограф</option>
        <option value="student">студент</option>
    </select>
    <p>твій email</p>
    <input type="email" name="email" maxlength="50" required placeholder="введи email" class="box">
    </div>

    <div class="col">
    <p>твій пароль</p>
    <input type="password" name="pass" maxlength="20" required placeholder="введи пароль" class="box">
    <p>повтори пароль</p>
    <input type="password" name="c_pass" maxlength="20" required placeholder="повтори пароль" class="box">
    <p>обери фото <span>*</span></p>
    <input type="file" name="image" class="box" required accept="image/*">
    </div>
</div>
<input type="submit" value="зареєструватись" name="submit" class="btn" >
<p class="link">вже маєш акаунт? <a href="login.php">увійти</a></p>

</form>

</section>















































<script src="../js/admin_script.js"></script>


</body>
</html>



