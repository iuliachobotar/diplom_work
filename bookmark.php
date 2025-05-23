<?php 
session_start();
include 'components/connect.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
    header('location:home.php');
}

if(isset($_POST['delete'])){

    if($user_id != ''){

        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
    
        $verify_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
        $verify_list->execute([$user_id, $delete_id]);

        if($verify_list->rowCount() > 0){
            $remove_list = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
            $remove_list->execute([$user_id, $delete_id]);
            $message[] = 'playlist removed!';
        }else{ 
            $message[] = 'playlist already removed!';
            
        }



        
    }else{
        $message[] = 'please login first!';
    }

}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarked</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>


<section class="course">

    <h1 class="heading">збережено плейлистів</h1>

    <div class="box-container">

        <?php 
        $select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
        $select_bookmark->execute([$user_id]);
        if($select_bookmark->rowCount() > 0){
            while($fetch_bookmark = $select_bookmark->fetch(PDO::FETCH_ASSOC)){

        $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND status = ? ORDER BY date DESC");
        $select_courses->execute([$fetch_bookmark['playlist_id'], 'active']);
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
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="delete_id" value="<?= $course_id; ?>">
                    <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">переглянути</a>
                    <input type="submit" value="вилучити" name="delete" class="inline-delete-btn" onclick="return confirm('remove from bookmark?');">
                </form>
            </div>

        <?php 
            }
        }else{
            echo '<p class="empty">курсів ще не додано!</p>';
        }
    }
        }else{
            echo '<p class="empty">ще нічого не збережено!</p>';
        }
        ?>

    </div>

</section>
























<script src="js/script.js"></script>

</body>
</html>