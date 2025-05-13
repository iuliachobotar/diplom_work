<?php
$db_name = 'mysql:host=localhost;dbname=course_db';
$db_user_name = 'root';
$db_user_pass = '';

$conn = new PDO($db_name, $db_user_name, $db_user_pass);

function create_unique_id() {
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $rand = array();
    $length = strlen($str) - 1;
    for ($i = 0; $i < 20; $i++) {
        $n = mt_rand(0, $length);
        $rand[] = $str[$n];
    }
    return implode($rand);
}

session_start();
if (!isset($_SESSION['tutor_id'])) {
    die('Tutor ID is not set. Please log in.');
}

$tutor_id = $_SESSION['tutor_id'];

if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_folder = '../uploaded_files/' . $rename;

    $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND title = ? AND description = ? ");
    $verify_playlist->execute([ $tutor_id, $title, $description]);

    if ($verify_playlist->rowCount() > 0) {
        $message[] = 'плейлист вже створено!';
    } else {
        $add_playlist = $conn->prepare("INSERT INTO `playlist` (id, tutor_id, title, description, thumb, status) VALUES (?, ?, ?, ?, ?, ?)");
        $add_playlist->execute([$id, $tutor_id, $title, $description, $rename, $status]);
        move_uploaded_file($thumb_tmp_name, $thumb_folder);
        $message[] = 'створено новий плейлист!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Playlist</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>


<section class="crud-form">

<h1 class="heading">додати плейлист</h1>

<form action="" method="POST" enctype="multipart/form-data">
<p>статус плейлиста <span>*</span></p>
<select name="status" required class="box">
    <option value="active">активний</option>
    <option value="deactive">неактивний</option>
</select>
    <p>заголовок <span>*</span></p>
    <input type="text" class="box" name="title" maxlength="100" placeholder="веди заголовок">
    <p>опис плейлиста <span>*</span></p>
    <textarea name="description" class="box" cols="30" required placeholder="введи опис плейлиста" maxlength="1000" rows="10" ></textarea>
    <p>обкладинка плейлиста <span>*</span></p>
    <input type="file" name="thumb" required accept="image/*" class="box">
    <input type="submit" value="створити" name="submit" class="btn">
</form>

</section>













































<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>


</body>
</html>



