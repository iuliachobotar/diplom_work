<?php
session_start();
include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
$tutor_id = $_SESSION['tutor_id'];
}else{
    $tutor_id = '';
    header('location:login.php');
}

if(isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
}else{
    $get_id = '';
    header('location:playlists.php');
    exit();
}

if(isset($_POST['delete_playlist'])){
    $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
    $verify_playlist->execute([$get_id]);

    if($verify_playlist->rowCount() > 0){
        $fetch_thumb = $verify_playlist->fetch(PDO::FETCH_ASSOC);
        $prev_thumb = $fetch_thumb['thumb'];
        if($prev_thumb != ''){
            unlink('../uploaded_files/' .$prev_thumb);
        }
        $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
        $delete_bookmark->execute([$get_id]);
        $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
        $delete_playlist->execute( [$get_id]);

        header('location:playlists.php');
        exit();
    }else{
        $message[] = 'playlist was already deleted!';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Playlist</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlist-details">

    <h1 class="heading">playlist details</h1>

<?php 
    $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
    $select_playlist->execute([$get_id]);
    
    if($select_playlist->rowCount() > 0){
        while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $count_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_content->execute([$get_id]);
            $total_contents = $count_content->rowCount();
?>
<div class="row">
    <div class="thumb">
        
        <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
        <div class="flex">
        <p><i class="fas fa-video"></i><span><?= $total_contents; ?></span></p>
        <p><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></p>
        </div>
    </div>
    <div class="details">
            <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
            <p class="description"><?= $fetch_playlist['description']; ?></p>
            <form action="" method="POST" class="flex-btn">
                <input type="hidden" name="delete_id" value="<?= $fetch_playlist['id']; ?>">
                <a href="update_playlist.php?get_id=<?= $fetch_playlist['id']; ?>" class="option-btn">update</a>
                <input type="submit" value="delete" name="delete_playlist" class="delete-btn">
            </form>
    </div>
</div>

<?php
        }
    }else{
        echo '<p class="empty">playlist was not found!</p>';
    }
?>

</section>

<section class="contents">

    <h1 class="heading">playlist contents</h1>

    <div class="box-container">

    <?php 
    $select_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND playlist_id = ?");
    $select_content->execute([$tutor_id, $get_id]);
    if($select_content->rowCount() > 0){
        while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
    ?> 

    <div class="box">
            <div class="flex">
                
            </div>
    </div>

    <?php 
            }
        }else{
            echo '<p class="empty">no contents added yet! <a href="add_content.php" style="margin-top: 1.5rem;" class="btn">add new content</a></p>';
        }
    ?>


    </div>

</section>












































<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>


</body>
</html>



