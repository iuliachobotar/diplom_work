<?php 
session_start();
include 'components/connect.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
    header('location:home.php');
}

$count_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$count_bookmark->execute([$user_id]);
$total_bookmarks = $count_bookmark->rowCount();

$count_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$count_likes->execute([$user_id]);
$total_likes = $count_likes->rowCount();

$count_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$count_comments->execute([$user_id]);
$total_comments = $count_comments->rowCount();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="profile">
    <h1 class="heading">деталі профілю</h1>

    <div class="details">
    
    <div class="tutor">
        <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
        <h3><?= $fetch_profile['name']; ?></h3>
        <p><?= $fetch_profile['email']; ?></p>
        <span>студент</span>
        <a href="update.php" class="inline-btn">редагувати профіль</a>

    </div>

    <div class="box-container">

    <div class="box">
        <h3><?= $total_bookmarks ?></h3>
        <p>плейлистів збережено</p>
        <a href="bookmark.php" class="btn">переглянути</a>
    </div>

    <div class="box">
        <h3><?= $total_likes ?></h3>
        <p>загально вподобано</p>
        <a href="likes.php" class="btn">переглянути</a>
    </div>

    <div class="box">
        <h3><?= $total_comments?></h3>
        <p>загально прокоментовано</p>
        <a href="comments.php" class="btn">переглянути</a>
    </div>

    </div>
    </div>
</section>
























<script src="js/script.js"></script>

</body>
</html>