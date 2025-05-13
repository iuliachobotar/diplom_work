<?php
session_start();
include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
    $tutor_id = $_SESSION['tutor_id'];
    }else{
        $tutor_id = '';
    }

    if(isset($_POST['submit'])){

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $verify_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE email = ? AND password = ? LIMIT 1");
        $verify_tutor->execute([$email, $pass]);
        $row = $verify_tutor->fetch(PDO::FETCH_ASSOC);

        if($verify_tutor->rowCount() > 0){
            setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:dashboard.php');
        }else{
            $message[] = 'incorrect email or password!';
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
    <h3>З поверненням!</h3>
    <p>твій email</p>
    <input type="email" name="email" maxlength="50" required placeholder="введіть email" class="box">
    </div>
    <p>твій пароль</p>
    <input type="password" name="pass" maxlength="20" required placeholder="введіть пароль" class="box">

<input type="submit" value="login now" name="submit" class="btn" >
<p class="link">не маєш акаунт? <a href="register.php">зареєструватись</a></p>

</form>

</section>















































<script src="../js/admin_script.js"></script>


</body>
</html>



