<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['tutor_id'])) {
    $tutor_id = $_SESSION['tutor_id'];
} else {
    $tutor_id = null; 
}

if (isset($message) && is_array($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

?>



<header class="header">

<section class="flex">

    <a href="home.php" class="logo">Educa.</a>

    <form action="search_course.php" method="post" class="search-form">
        <input type="text" placeholder="шукай тут..." required maxlength="100" name="search_box">
        <button type="submit" class="fas fa-search" name="search_btn"></button>
    </form>

    <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
        <div id="search-btn" class="fas fa-search"></div>
        <div id="user-btn" class="fas fa-user"></div>
        <div id="toggle-btn" class="fas fa-sun"></div>
    </div>

    <div class="profile">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->execute([$user_id]);
        if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
    <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
    <h3><?= $fetch_profile['name']; ?></h3>
    <span>студент</span>
    <a href="profile.php" class="btn">профіль</a>
    <div class="flex-btn">
            <a href="login.php" class="option-btn">вхід</a>
            <a href="register.php" class="option-btn">реєстрація</a>
    </div>
            <a href="components/user_logout.php" onclick="return confirm ('logout from this website?');" class="delete-btn">вийти</a>
        <?php
                }else{
        ?>
        <h3>будь ласка, спершу увійдіть</h3>
    <div class="flex-btn">
            <a href="login.php" class="option-btn">вхід</a>
            <a href="register.php" class="option-btn">реєстрація</a>
    </div>
        <?php
        }
        ?>
    </div>
</section>

</header>

<div class="side-bar">

        <div id="close-bar">
            <i class="fas fa-times"></i>
        </div>
    
<div class="profile">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->execute([$user_id]);
        if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
    <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
    <h3><?= $fetch_profile['name']; ?></h3>
    <span>студент</span>
    <a href="profile.php" class="btn">профіль</a>
        <?php
                }else{     
        ?>
        <h3>будь ласка, спершу увійдіть</h3>
    <div class="flex-btn">
            <a href="login.php" class="option-btn">вхід</a>
            <a href="register.php" class="option-btn">реєстрація</a>
    </div>
        <?php
        }
        ?>
    </div>

        <nav class="navbar">
            <a href="home.php"><i class="fas fa-home"></i><span>головна</span></a>
            <a href="about.php"><i class="fas fa-question"></i><span>про нас</span></a>
            <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>курси</span></a>
            <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>викладачі</span></a>
            <a href="contact.php"><i class="fas fa-headset"></i><span>контакти</span></a>
        </nav>


</div>