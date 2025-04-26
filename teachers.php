<?php 
session_start();
include 'components/connect.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="teachers">

    <h1 class="heading">expert tutors</h1>

    <form action="search_tutor.php" method="post" class="tutor-search">
        <input type="text" name="search_tutor_box" placeholder="search tutors" maxlength="100" required>
        <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
    </form>

    <div class="box-container">

    <div class="box offer">
        <h3>become a tutor</h3>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ab, illo!</p>
        <a href="admin/register.php" class="inline-btn">get started</a>
    </div>

    <?php 
        $select_tutors = $conn->prepare("SELECT * FROM `tutor`");
        $select_tutors->execute();
        if($select_tutors->rowCount() > 0) {
            while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){
            $tutor_id = $fetch_tutor['id'];

            $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
            $count_playlists->execute([$tutor_id]);
            $total_playlists = $count_playlists->rowCount();

            $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
            $count_likes->execute([$tutor_id]);
            $total_likes = $count_likes->rowCount();

            $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
            $count_comments->execute([$tutor_id]);
            $total_comments = $count_comments->rowCount();

            $count_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
            $count_content->execute([$tutor_id]);
            $total_content = $count_content->rowCount();
    ?>

    <div class="box">
        <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
                <h3><?= $fetch_tutor['name']; ?></h3>
                <span><?= $fetch_tutor['profession']; ?></span>
            </div>
        </div>
        <p>total videos : <span><?= $total_content; ?></span></p>
        <p>total courses : <span><?= $total_playlists; ?></span></p>
        <p>total likes : <span><?= $total_likes; ?></span></p>
        <p>total comment : <span><?= $total_comments; ?></span></p>
        <a href="tutor_profile.php?get_id=<?= $fetch_tutor['email']; ?>" class="inline-btn">view profile</a>
    </div>

    <?php 
        }
    }else{
        echo '<p class="empty">tutors was not found!</p>';
    }
    ?>

    </div>

</section>
























<script src="js/script.js"></script>

</body>
</html>