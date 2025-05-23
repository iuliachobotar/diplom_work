<?php
session_start();
include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
$tutor_id = $_SESSION['tutor_id'];
}else{
    $tutor_id = '';
    header('location:login.php');
}

if(isset($_POST['delete_playlist'])){

    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id,FILTER_SANITIZE_STRING);

    $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
    $verify_playlist->execute([$delete_id]);

    if($verify_playlist->rowCount() > 0){
        $fetch_thumb = $verify_playlist->fetch(PDO::FETCH_ASSOC);
        $prev_thumb = $fetch_thumb['thumb'];
        if($prev_thumb != ''){
            unlink('../uploaded_files/' .$prev_thumb);
        }
        $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
        $delete_bookmark->execute([$delete_id]);
        $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
        $delete_playlist->execute([$delete_id]);
        $message[] = 'playlist deleted';
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
    <title>All Playlists</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists">

<h1 class="heading">всі плейлисти</h1>

<div class="box-container">

    <div class="box" style="text-align: center;">
        <h3 class="title" style="padding-bottom: .7rem;">створити плейлист</h3>
        <a href="add_playlist.php" class="btn">додати плейлист</a>
    </div>

<?php 
    $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
    $select_playlist->execute([$tutor_id]);
    if($select_playlist->rowCount() > 0){
        while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){

            $playlist_id = $fetch_playlist['id'];

            $count_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_content->execute([$playlist_id]);
            $total_contents = $count_content->rowCount();

?>
<div class="box">
    <div class="flex">
        <div><i class="fas fa-circle-dot" style="color:<?php if($fetch_playlist['status'] == 'active'){echo 'limegreen';}else{echo 'red';}?>;"></i><span style="color:<?php if($fetch_playlist['status'] == 'active'){echo 'limegreen';}else{echo 'red';}?>;"><?= $fetch_playlist['status'];?></span></div>
        <div><i class="fas fa-calendar" ></i><span><?= $fetch_playlist['date'];?></span></div>
    </div>
            <div class="thumb">
                <span><?= $total_contents; ?></span>
                <img src="../uploaded_files/<?= $fetch_playlist['thumb'];?>" alt="">
            </div>
            <h3 class="title"><?= $fetch_playlist['title'];?></h3>
            <p class="description"><?= $fetch_playlist['description'];?></p>
            <form action="" method="POST" class="flex-btn">
                <input type="hidden" name="delete_id" value="<?= $playlist_id; ?>">
                <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">редагувати</a>
                <input type="submit" value="видалити" name="delete_playlist" class="delete-btn">
            </form>
            <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">переглянути</a>

</div>
<?php
        }
    }else{
        echo '<p class="empty">playlist not created added yet!</p>';
    }
?>

</div>

</section>














































<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>

document.querySelectorAll('.description').forEach(content =>{
    if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
});

</script>


</body>
</html>



