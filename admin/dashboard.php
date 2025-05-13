<?php
session_start();
include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
$tutor_id = $_SESSION['tutor_id'];
}else{
    $tutor_id = '';
    header('location:login.php');
}

$count_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$count_content->execute([$tutor_id]);
$total_contents = $count_content->rowCount();

$count_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$count_playlist->execute([$tutor_id]);
$total_playlists = $count_playlist->rowCount();

$count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$count_likes->execute([$tutor_id]);
$total_likes = $count_likes->rowCount();

$count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$count_comments->execute([$tutor_id]);
$total_comments = $count_comments->rowCount();

$select_profile = $conn->prepare("SELECT * FROM `tutor` WHERE id = ?");
$select_profile->execute([$tutor_id]);

if ($select_profile->rowCount() > 0) {
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
} else {
    $fetch_profile = ['name' => 'Unknown Tutor'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

    <h1 class="heading">панель приладів</h1>

    <div class="box-container">

    <div class="box">
        <h3>з поверненням!</h3>
        <p><?= $fetch_profile['name']; ?></p>
        <a href="profile.php" class="btn">переглянути профіль</a>
    </div>

    <div class="box">
        <h3><?= $total_contents; ?></h3>
        <p>завантажений міст</p>
        <a href="add_content.php" class="btn">створити контент</a>
    </div>

    <div class="box">
        <h3><?= $total_playlists; ?></h3>
        <p>завантажено плейлистів</p>
        <a href="add_playlist.php" class="btn">створити плейлист</a>
    </div>

    <div class="box">
        <h3><?= $total_likes; ?></h3>
        <p>загально вподобань</p>
        <a href="contents.php" class="btn">переглянути</a>
    </div>

    <div class="box">
        <h3><?= $total_likes; ?></h3>
        <p>загально коментарів</p>
        <a href="comments.php" class="btn">переглянути</a>
    </div>
    <div class="box">
        <h3>швидкі посилання</h3>
        <p>вхід чи реєстрація</p>
        <div class="flex-btn">
            <a href="login.php" class="option-btn">вхід</a>
            <a href="register.php" class="option-btn">реєстрація</a>
        </div>
    </div>

    </div>

</section>















































<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>


</body>
</html>



