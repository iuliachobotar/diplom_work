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
    <title>Search Courses</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="course">

    <h1 class="heading">результати пошуку</h1>

    <div class="box-container">

        <?php 
    if(isset($_POST['search_box']) or isset($_POST['search_btn'])){
    $search_box = $_POST['search_box'];

        $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE title LIKE '%{$search_box}%' AND status = ? ORDER BY date DESC");
        $select_courses->execute(['active']);
        if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
                $course_id = $fetch_course['id'];

                $count_course = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
                $count_course->execute([$course_id]);
                $total_courses = $count_course->rowCount();

                $select_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE id = ?");
                $select_tutor->execute([$fetch_course['tutor_id']]);
                $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class="box">
                <div class="tutor">
                    <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
                    <div>
                        <h3><?= $fetch_tutor['name']; ?></h3>
                        <span><?= $fetch_course['date']; ?></span>
                    </div>
                </div>
                <div class="thumb">
                    <span><?= $total_courses; ?></span>
                    <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" alt="">
                </div>
                <h3 class="title"><?= $fetch_course['title']; ?></h3>
                <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">переглянути</a>
        </div>

        <?php 
            }
        }else{
            echo '<p class="empty">result not found!</p>';
        }
    }else{
        echo '<p class="empty">please search something!</p>';
    }
        ?>

    </div>

</section>
























<script src="js/script.js"></script>

</body>
</html>