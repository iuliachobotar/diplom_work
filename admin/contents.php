<?php
session_start();
include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
$tutor_id = $_SESSION['tutor_id'];
}else{
    $tutor_id = '';
    header('location:login.php');
}

if(isset($_POST['delete_content'])){
    $delete_id = $_POST['content_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
    $verify_content->execute([$delete_id]);

    if($verify_content->rowCount() > 0){
        $fetch_content = $verify_content->fetch(PDO::FETCH_ASSOC);
        unlink('../uploaded_files/'.$fetch_content['thumb']);
        unlink('../uploaded_files/'.$fetch_content['video']);
        $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
        $delete_comment->execute([$delete_id]);
        $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
        $delete_likes->execute([$delete_id]);
        $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ? LIMIT 1");
        $delete_content->execute([$delete_id]);
        $message[] = 'content deleted successfully!';
    }else{
        $message[] = 'content already deleted!';
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Content</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="contents">

    <h1 class="heading">всі контенти</h1>

    <div class="box-container">

    <?php 
    $select_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
    $select_content->execute([$tutor_id]);
    if($select_content->rowCount() > 0){
        while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
    ?> 

    <div class="box">
            <div class="flex">
                <p><i class="fas fa-circle-dot" style="color:<?php if($fetch_content['status'] == 'active'){echo 'limegreen';}else{echo 'red';}?>;"></i><span style="color:<?php if($fetch_content['status'] == 'active'){echo 'limegreen';}else{echo 'red';}?>;"><?= $fetch_content['status']; ?></span></p>
                <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
            </div>
            <img src="../uploaded_files/<?= $fetch_content['thumb']; ?>" alt="">
            <h3 class="title"><?= $fetch_content['title']; ?></h3>
            <a href="view_content.php?get_id=<?= $fetch_content['id']; ?>" class="btn">переглянути</a>
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>">
                    <a href="update_content.php?get_id=<?= $fetch_content['id']; ?>" class="option-btn">редагувати</a>
                    <input type="submit" value="видалити" name="delete_content" class="delete-btn">
                </form>
    </div>

    <?php 
            }
        }else{
            echo '<p class="empty">контент ще не доданий! <a href="add_content.php" style="margin-top: 1.5rem;" class="btn">add new content</a></p>';
        }
    ?>


    </div>

</section>














































<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>


</body>
</html>


