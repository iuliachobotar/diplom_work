<?php 
session_start();
include 'components/connect.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

if(isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
}else{
    $get_id = '';
    header('location:courses.php');
}

if(isset($_POST['like_content'])){
    if($user_id != ''){

        $like_id = $_POST['content_id'];
        $like_id = filter_var($like_id, FILTER_SANITIZE_STRING);

        $get_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
        $get_content->execute([$like_id]);
        $fetch_get_content = $get_content->fetch(PDO::FETCH_ASSOC);

        $tutor_id = $fetch_get_content['tutor_id'];

        $verify_like = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
        $verify_like->execute([$user_id, $like_id]);

        if($verify_like->rowCount() > 0){
            $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
            $remove_likes->execute([$user_id, $like_id]);
            $message[] = 'вилучено з вподобаного!';
        }else{
            $add_likes = $conn->prepare("INSERT INTO `likes` (user_id, tutor_id, content_id) VALUES (?, ?, ?)");
            $add_likes->execute([$user_id, $tutor_id, $like_id]);
            $message[] = 'додано до вподобаних!';
        }

    }else{
        $message[] = 'будь ласка, спочатку авторизуйтесь!';
    }
}

if(isset($_POST['add_comment'])){

    $id = create_unique_id();

    $comment_box = $_POST['comment_box'];
    $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);

    $select_content_tutor = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
    $select_content_tutor->execute([$get_id]);
    $fetch_content_tutor_id = $select_content_tutor->fetch(PDO::FETCH_ASSOC);
    $content_tutor_id = $fetch_content_tutor_id['tutor_id'];

    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? AND user_id = ? AND tutor_id = ? AND comment = ?");
    $verify_comment ->execute([$get_id, $user_id, $content_tutor_id, $comment_box]);

    if($verify_comment->rowCount() > 0){
        $message[] = 'коментар вже додано!';
    }else{
        $add_comment = $conn->prepare("INSERT INTO `comments` (id, content_id, user_id, tutor_id, comment) VALUES(?, ?, ?, ?, ?)");
        $add_comment->execute([$id, $get_id, $user_id, $content_tutor_id, $comment_box]);
        $message[] = 'коментар додано успішно!';
    }
}

if(isset($_POST['delete_comment'])){
    $delete_id = $_POST['comment_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
    $verify_comment->execute([$delete_id]);

    if($verify_comment->rowCount() > 0){
        $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ? LIMIT 1");
        $delete_comment->execute([$delete_id]);
        $message[] = 'коментар успішно видалено!';
    }else{
        $message[] = 'коментар вже видалено!';
    }
}

if(isset($_POST['edit_comment'])){

    $edit_id = $_POST['edit_id'];
    $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);

    $comment_box = $_POST['comment_box'];
    $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);

    $verify_edit_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND comment = ? ");
    $verify_edit_comment->execute([$edit_id, $comment_box]);

    if($verify_edit_comment->rowCount() > 0){
        $message[] = 'коментар вже додано!';
    }else{
        $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ? LIMIT 1");
        $update_comment->execute([$comment_box, $edit_id]);
        $message[] = 'коментар успішно оновлено!';
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Video</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<?php 
    if(isset($_POST['update_comment'])){
        $update_id = $_POST['comment_id'];
        $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
        $select_update_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? LIMIT 1 ");
        $select_update_comment->execute([$update_id]);
        $fetch_update_comment = $select_update_comment->fetch(PDO::FETCH_ASSOC);
    
?>

<section class="comment-form">

    <h1 class="heading">редагувати</h1>

    <form action="" method="post">
        <input type="hidden" name="edit_id" value="<?= $fetch_update_comment['id']; ?>">
        <textarea name="comment_box" class="box" required maxlength="1000" placeholder="enter your comment" cols="30" rows="10"><?= $fetch_update_comment['comment']; ?></textarea>
        <div class="flex-btn">
            <a href="watch_video.php?get_id=<?= $get_id; ?>" class="inline-option-btn">скасувати зміни</a>
        <input type="submit" value="редагувати" name="edit_comment" class="inline-btn" >
        </div>
    </form>

</section>

<?php 
    }
?>

<section class="watch-video">

    <?php 
        $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND status = ?");
        $select_content->execute([$get_id, 'active']);
        if($select_content->rowCount() > 0){
            while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
            $content_id = $fetch_content['id'];

            $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE content_id = ?");
            $select_likes->execute([$content_id]);
            $total_likes = $select_likes->rowCount();

            $user_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
            $user_likes->execute([$user_id, $content_id]);


            $select_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE id = ?");
            $select_tutor->execute([$fetch_content['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="content">
        <video src="uploaded_files/<?= $fetch_content['video']; ?>" controls autoplay poster="uploaded_files/<?= $fetch_content['thumb']; ?>" class="video"></video>
        <h3 class="title"><?= $fetch_content['title']; ?></h3>
        <div class="info">
            <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
            <p><i class="fas fa-heart"></i><span><?= $total_likes; ?></span></p>
        </div>
        <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
                <h3><?= $fetch_tutor['name']; ?></h3>
                <span><?= $fetch_tutor['profession']; ?></span>
            </div>
        </div>
        <form action="" method="post" class="flex">
            <input type="hidden" name="content_id" value="<?= $content_id; ?>">
            <a href="playlist.php?get_id=<?= $fetch_content['playlist_id']; ?>" class="inline-btn">весь плейлист</a>
            <?php if ($user_likes->rowCount() > 0){ ?>
            <button type="submit" class="inline-btn" name="like_content"><i class="fas fa-heart"></i><span>вподонано</span></button>
            <?php }else {?>
            <button type="submit" class="inline-option-btn" name="like_content"><i class="far fa-heart"></i><span>вподабати</span></button>
            <?php } ?>
        </form>
        <p class="description"><?= $fetch_content['description']; ?></p>

    </div>

    <?php 
        }
    }else{
        echo '<p class="empty">не знайдено жодного вмісту!</p>';
    }
    ?>

</section>

<section class="comment-form">

    <h1 class="heading">додати коментар</h1>

    <form action="" method="post">
        <textarea name="comment_box" class="box" required maxlength="1000" placeholder="впишіть коментар" cols="30" rows="10"></textarea>
        <input type="submit" value="додати коментар" name="add_comment" class="inline-btn" >
    </form>

</section>

<section class="comments">

        <h1 class="heading">коментарі користувачів</h1>

        <div class="box-container">
            <?php
                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
                $select_comments->execute([$get_id]);
                if($select_comments->rowCount() > 0){
                    while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
                        $comment_id = $fetch_comment['id'];
                        $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                        $select_commentor->execute([$fetch_comment['user_id']]);
                        $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);

            ?> 
                <div class="box" <?php if($fetch_comment['user_id'] == $user_id){echo 'style="order:-1;"'; }; ?>                    >
                    <div class="user">
                        <img src="uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
                        <div>
                            <h3><?= $fetch_commentor['name']; ?></h3>
                            <span><?= $fetch_comment['date']; ?></span>
                        </div>
                    </div>
                    <p class="comment-box"><?= $fetch_comment['comment']; ?></p>
                         <?php if($fetch_comment['user_id'] == $user_id){ ?>
                    <form action="" method="post">
                        <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
                        <input type="submit" value="редагувати" name="update_comment" class="inline-option-btn">
                        <input type="submit" value="видалити" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">
                    </form>         
                    <?php } ?>   
                </div>

            <?php 
                }
                }else{
                    echo'<p class="empty">коментарів ще не додано!</p>';
                }
            ?>



        </div>
</section>























<script src="js/script.js"></script>

</body>
</html>