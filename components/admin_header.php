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

    <a href="dashboard.php" class="logo">Admin.</a>

    <form action="search_page.php" method="post" class="search-form">
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
        $select_profile = $conn->prepare("SELECT * FROM `tutor` WHERE id = ?");
        $select_profile->execute([$tutor_id]);
        if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
    <h3><?= $fetch_profile['name']; ?></h3>
    <span><?= $fetch_profile['profession']; ?></span>
    <a href="profile.php" class="btn">переглянути профіль</a>
    <div class="flex-btn">
            <a href="login.php" class="option-btn">вхід</a>
            <a href="register.php" class="option-btn">реєстрація</a>
    </div>
            <a href="../components//admin_logout.php" onclick="return confirm ('logout from this website?');" class="delete-btn">logout</a>
        <?php
                }else{
        ?>
        <h3>будь ласка, увійдіть спочатку</h3>
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
        $select_profile = $conn->prepare("SELECT * FROM `tutor` WHERE id = ?");
        $select_profile->execute([$tutor_id]);
        if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
    <h3><?= $fetch_profile['name']; ?></h3>
    <span><?= $fetch_profile['profession']; ?></span>
    <a href="profile.php" class="btn">переглянути профіль</a>
        <?php
                }else{     
        ?>
        <h3>будь ласка, увійдіть спочатку</h3>
    <div class="flex-btn">
            <a href="login.php" class="option-btn">вхід</a>
            <a href="register.php" class="option-btn">реєстрація</a>
    </div>
        <?php
        }
        ?>
    </div>

        <nav class="navbar">
            <a href="dashboard.php"><i class="fas fa-home"></i><span>головна</span></a>
            <a href="playlists.php"><i class="fas fa-bars-staggered"></i><span>плейлисти</span></a>
            <a href="contents.php"><i class="fas fa-graduation-cap"></i><span>контенти</span></a>
            <a href="comments.php"><i class="fas fa-comment"></i><span>коментарі</span></a>
            <a href="../components//admin_logout.php" onclick="return confirm ('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>вийти</span></a>
        </nav>


</div>