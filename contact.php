<?php 
session_start();
include 'components/connect.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);

    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);

    $verify_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ?	AND email = ? AND number = ? AND message = ?");
    $verify_contact->execute([$name, $email, $number, $msg]);

    if($verify_contact->rowCount() > 0){
        $message[] = 'message sent already!';
    }else{
        $send_message = $conn->prepare("INSERT INTO `contact` (name, email, number, message	) VALUES (?, ?, ?, ?)");
        $send_message->execute([$name, $email, $number, $msg]);
        $message[] = 'message sent successffully!';
    }

}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зв'яжіться з нами</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="contact">

    <div class="row">

        <div class="image">
            <img src="images/contact-img.svg" alt="">
        </div>

        <form action="" method="post">
            <h3>Зв'яжіться із нами</h3>
            <input type="text" class="box" required maxlength="50" name="name" placeholder="впишіть ім'я">
            <input type="email" class="box" required maxlength="50" name="email" placeholder="впишіть email">
            <input type="number" class="box" required maxlength="10" name="number" placeholder="впишіть номер">
            <textarea name="msg" class="box" required maxlength="1000" placeholder="впишіть повідомлення" cols="30" rows="10"></textarea>
            <input type="submit" value="надіслати" class="inline-btn" name="submit">


        </form>

    </div>

    <div class="box-container">

    <div class="box">
        <i class="fas fa-phone"></i>
        <h3>номер телефону</h3>
        <a href="tel:1234567890">123-456-7890</a>
        <a href="tel:1112223333">111-222-3333</a>
    </div>

    <div class="box">
        <i class="fas fa-envelope"></i>
        <h3>email</h3>
        <a href="mailto:chobotarlyly@gmail.com">chobotarlyly@gmail.com</a>
        <a href="mailto:chobotar_ly@gmail.com">chobotar_ly@gmail.com</a>
    </div>

    <div class="box">
        <i class="fas fa-map-marker-alt"></i>
        <h3>адреса</h3>
        <a href="#">56 Potebni Street, Lutsk, Volyn region</a>
    </div>

    </div>

</section>
























<script src="js/script.js"></script>

</body>
</html>