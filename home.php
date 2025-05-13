<?php 
session_start();
include 'components/connect.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

$cout_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$cout_likes->execute([$user_id]);
$total_likes = $cout_likes->rowCount();

$cout_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$cout_comments->execute([$user_id]);
$total_comments = $cout_comments->rowCount();

$cout_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$cout_bookmark->execute([$user_id]);
$total_bookmark = $cout_bookmark->rowCount();


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'components/user_header.php'; ?>

<section class="quick-select">

    <h1 class="heading">швидкі опції</h1>

    <div class="box-container">

    <?php if($user_id != ''){ ?>
        <div class="box">
            <h3 class="title">лайки та коментарі</h3>
            <p>всього лайків : <span><?= $total_likes; ?></span></p>
            <a href="likes.php" class="inline-btn">переглянути</a>
            <p>всього коментарів : <span><?= $total_comments; ?></span></p>
            <a href="comments.php" class="inline-btn">переглянути</a>
            <p>плейлистів збережено : <span><?= $total_bookmark; ?></span></p>
            <a href="bookmark.php" class="inline-btn">переглянути</a>
        </div>
        <?php }else{ ?>
            <div class="box" style="text-align: center;">
            <h3 class="title">увійдіть або зареєструйтесь</h3>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">вхід</a>
                <a href="register.php" class="option-btn">реєстрація</a>
            </div>
        </div>
        <?php } ?>

        <div class="box">
            <h3 class="title">топ категорій</h3>
            <div class="flex">
            <a href="courses.php"><i class="fas fa-code"></i><span>розробка</span></a>
            <a href="courses.php"><i class="fas fa-chart-simple"></i><span>бізнес</span></a>
            <a href="courses.php"><i class="fas fa-pen"></i><span>дизайн</span></a>
            <a href="courses.php"><i class="fas fa-chart-line"></i><span>маркетинг</span></a>
            <a href="courses.php"><i class="fas fa-music"></i><span>музика</span></a>
            <a href="courses.php"><i class="fas fa-camera"></i><span>фотографії</span></a>
            <a href="courses.php"><i class="fas fa-cog"></i><span>програмне забезпечення</span></a>
            <a href="courses.php"><i class="fas fa-vial"></i><span>наука</span></a>
            </div>
        </div>

        <div class="box">
            <h3 class="title">популярні теми</h3>
            <div class="flex">
            <a href="courses.php"><i class="fab fa-figma"></i><span>figma</span></a>
            <a href="courses.php"><i class="fa-solid fa-palette"></i><span>Photoshop</span></a>
            <a href="courses.php"><i class="fa-solid fa-pen-nib"></i><span>Illustrator</span></a>

            <a href="courses.php"><i class="fab fa-html5"></i><span>HTML</span></a>
            <a href="courses.php"><i class="fa-brands fa-css3-alt"></i><span>CSS</span></a>
            <a href="courses.php"><i class="fab fa-js"></i><span>javascript</span></a>
            <a href="courses.php"><i class="fab fa-react"></i><span>react</span></a>
            <a href="courses.php"><i class="fab fa-php"></i><span>PHP</span></a>
            <a href="courses.php"><i class="fab fa-bootstrap"></i><span>bootstrap</span></a>

            </div>
        </div>

        <div class="box tutor">
            <h3 class="title">стати викладачем</h3>
            <p>Приєднуйтесь до нашої освітньої платформи та діліться знаннями з тисячами студентів.</p>
            <a href="admin/register.php" class="inline-btn">Розпочати</a>
        </div>

    </div>

</section>

<section class="course">

    <h1 class="heading">останні курси</h1>

    <div class="box-container">

        <?php 
        $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
        $select_courses->execute(['active']);
        if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
                $course_id = $fetch_course['id'];

                $count_course = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ?");
                $count_course->execute([$course_id, 'active']);
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
            echo '<p class="empty">no courses added yet!</p>';
        }
        ?>

    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <a href="courses.php" class="inline-option-btn">Переглянути всі</a>
    </div>

</section>



























<script src="js/script.js"></script>

</body>
</html>