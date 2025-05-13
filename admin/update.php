<?php
session_start();
include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
$tutor_id = $_SESSION['tutor_id'];
}else{
    $tutor_id = '';
    header('location:login.php');
}

if(isset($_POST['submit'])){

    $select_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE id = ? LIMIT 1");
    $select_tutor->execute([$tutor_id]);
    $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    
    if(!empty($name)){
        $update_name = $conn->prepare("UPDATE `tutor` SET name = ? WHERE id = ?");
        $update_name->execute([$name, $tutor_id]);
        $message[] = 'ім*я успішно оновлено!';
    }

    if(!empty($profession)){
        $update_profession = $conn->prepare("UPDATE `tutor` SET profession = ? WHERE id = ?");
        $update_profession->execute([$profession, $tutor_id]);
        $message[] = 'професія успішно оновлена!';
    }

    if(!empty($email)){
        $select_tutor_email = $conn->prepare("SELECT * FROM `tutor` WHERE email = ?");
        $select_tutor_email->execute([$email]);
        if($select_tutor_email->rowCount() > 0){
            $message[] = 'адреса вже використовується!';
        }else{
        $update_email = $conn->prepare("UPDATE `tutor` SET email = ? WHERE id = ?");
        $update_email->execute([$email, $tutor_id]);
        $message[] = 'електронну пошту успішно оновлено!';
        }

        
    }

    $prev_image = $fetch_tutor['image'];
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = '../uploaded_files/' . $rename;

    if(!empty($image)){
        if($image_size > 2000000){
            $message[] = 'фото дуже велике!';
        }else{
            $update_image = $conn->prepare("UPDATE `tutor` SET image = ? WHERE id = ?");
            $update_image->execute([$rename, $tutor_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            if($prev_image != '' AND $prev_image != $rename){
                unlink('../uploaded_files/'.$prev_image);
            }
            $message[] = 'фото успішно оновлено!';
        }
    }

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $fetch_tutor['password'];
    $new_pass = sha1($_POST['new_pass']);
    $old_pass = sha1($_POST['old_pass']);
    $c_pass = sha1($_POST['c_pass']);

    if($old_pass != $empty_pass){
        if($old_pass != $prev_pass){
            $message[] = 'старий пароль не збігається!';
        }elseif($new_pass != $c_pass){
            $message[] = 'пароль не збігається!';
        }else{
            if($new_pass != $empty_pass){
                $update_pass = $conn->prepare("UPDATE `tutor` SET password = ? WHERE id = ?");
                $update_pass->execute([$c_pass, $tutor_id]);
                $message[] = 'пароль успішно оновлено!';
            }else{
                $message[] = 'будь ласка, введіть новий пароль';
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
    <title>Profile</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

<form action="" method="post" enctype="multipart/form-data">
    <h3>редагувати профіль</h3>
<div class="flex">
    <div class="col">
    <p>твоє ім'я</p>
    <input type="text" name="name" maxlength="50" placeholder="<?= $fetch_profile['name']; ?>" class="box">
    <p>твоя професія</p>
    <select name="profession" class="box">
        <option value="" selected><?= $fetch_profile['profession']; ?></option>
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
    <input type="email" name="email" maxlength="50" placeholder="<?= $fetch_profile['email']; ?>" class="box">
    </div>

    <div class="col">
    <p>старий пароль</p>
    <input type="password" name="old_pass" maxlength="20" placeholder="введи старий пароль" class="box">
    
    <p>твій пароль</p>
    <input type="password" name="new_pass" maxlength="20" placeholder="введи новий пароль" class="box">
    <p>повтори пароль</p>
    <input type="password" name="c_pass" maxlength="20" placeholder="повтори новий пароль" class="box">
    
</div>
</div>
<p>select pic</p>
    <input type="file" name="image" class="box"  accept="image/*">
<input type="submit" value="оновити профіль" name="submit" class="btn" >
</form>

</section>














































<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>


</body>
</html>



