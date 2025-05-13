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

<section class="profile">
    <h1 class="heading">деталі профілю</h1>

    <div class="details">
    
    <div class="tutor">
        <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
        <h3><?= $fetch_profile['name']; ?></h3>
        <span><?= $fetch_profile['profession'] ?></span>
        <a href="update.php" class="inline-btn">редагувати профіль</a>

    </div>

    <div class="box-container">

    <div class="box">
        <h3><?= $total_contents ?></h3>
        <p>загально контену</p>
        <a href="contents.php" class="btn">переглянути</a>
    </div>

    <div class="box">
        <h3><?= $total_playlists ?></h3>
        <p>загально плейлистів</p>
        <a href="playlists.php" class="btn">переглянути</a>
    </div>

    <div class="box">
        <h3><?= $total_likes ?></h3>
        <p>загально вподобано</p>
        <a href="contents.php" class="btn">переглянути</a>
    </div>

    <div class="box">
        <h3><?= $total_comments?></h3>
        <p>загально коментарів</p>
        <a href="comments.php" class="btn">переглянути</a>
    </div>

    </div>
    </div>
</section>














































<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>


</body>
</html>



