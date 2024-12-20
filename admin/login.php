<?php

include '../components/connect.php';
if(isset($_COOKIE['tutor_id'])){
    $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
    }

    if(isset($_POST['submit'])){

        $id = create_unique_id();
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $profession = $_POST['profession'];
        $profession = filter_var($profession, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $c_pass = sha1($_POST['c_pass']);
        $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = create_unique_id().'.'.$ext;
        $image_tmp_nname = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_folder = '../uploaded_files/'.$rename;

        $select_tutor_email = $conn->prepare("SELECT * FROM `tutor` WHERE email = ?");
        $select_tutor_email->execute([$email]);

        if($select_tutor_email->rowCount() > 0){
            $message [] = 'email already taken!';
        }else{
            if($pass != $c_pass){
                $message[] = 'password not matched!';
            }else{
                
                if($image_size > 2000000){
                    $message[] = 'image size to large!';
                }else{
                    $insert_tutor = $conn->prepare("INSERT INTO `tutor` (id, name, profession, email, password, image)VALUES(?,?,?,?,?,?)");
                    $insert_tutor->execute([$id, $name, $profession, $email, $c_pass, $rename]);
                    move_uploaded_file($image_tmp_name, $image_folder);


                    $verify_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE email = ? AND password = ? LIMIT 1");
                    $verify_tutor->execute([$email, $c_pass]);
                    $row = $verify_tutor->fetch(PDO::FETCH_ASSOC);

                    if($insert_tutor){
                        if($verify_tutor->rowCount() > 0){
                            setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
                            header('location:dashboard.php');
                        }else{
                            $message[] = 'something went wrong!';
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
    <title>Login</title>

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

<form action="" class="login" method="post" enctype="multipart/form-data">
    <h3>welcome back!</h3>
    <p>your email</p>
    <input type="email" name="email" maxlength="50" required placeholder="enter your email" class="box">
    </div>
    <p>your password</p>
    <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box">

<input type="submit" value="register now" name="submit" class="btn" >
<p class="link">don't have an account? <a href="register.php">register new</a></p>

</form>

</section>















































<script src="../js/admin_script.js"></script>


</body>
</html>



