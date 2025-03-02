<?php
session_start();
include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
$tutor_id = $_SESSION['tutor_id'];
}else{
    $tutor_id = '';
    header('location:login.php');
}

if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $playlist_id = filter_var($_POST['playlist_id'], FILTER_SANITIZE_STRING);

    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename_thumb = 'playlist_' . create_unique_id() . '.' . $thumb_ext;
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_folder = '../uploaded_files/' . $rename_thumb;

    $video = $_FILES['video']['name'];
    $video = filter_var($video, FILTER_SANITIZE_STRING);
    $video_ext = pathinfo($video, PATHINFO_EXTENSION);
    $rename_video ='playlist_' . create_unique_id() . '.' . $video_ext;
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = '../uploaded_files/' . $rename_video;

    $verify_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND title = ? AND description = ? ");
    $verify_content->execute([ $tutor_id, $title, $description]);

    if ($verify_content->rowCount() > 0) {
        $message[] = 'content already created!';
    } else {
        if($thumb_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            $add_content = $conn->prepare("INSERT INTO `content` (id, tutor_id, playlist_id, title, description, video, thumb, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $add_content->execute([$id, $tutor_id, $playlist_id, $title, $description, $rename_video, $rename_thumb, $status]);
            move_uploaded_file($thumb_tmp_name, $thumb_folder);
            move_uploaded_file($video_tmp_name, $video_folder);
            $message[] = 'New content created!';
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Content</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="crud-form">

    <h1 class="heading">add content</h1>

    <form action="" method="post" enctype="multipart/form-data">
    <p>content status <span>*</span></p>
    <select name="status" required class="box">
    <option value="active">active</option>
    <option value="deactive">deactive</option>
    </select>
    <p>content title <span>*</span></p>
    <input type="text" class="box" name="title" maxlength="100" placeholder="enter content title">
    <p>content description <span>*</span></p>
    <textarea name="description" class="box" cols="30" required placeholder="enter content description" maxlength="1000" rows="10" ></textarea>
    <select name="playlist_id" class="box" required>
        <option value="" disabled selected>--select playlist</option>
    <?php 
        $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
        $select_playlist->execute([$tutor_id]);
        if($select_playlist->rowCount() > 0){
            while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
    ?>
<option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
    <?php 
         }
        }else{
            echo '<option value="" disabled>no playlist created yet!</option>';
        }
    ?>

    </select>
    <p>select thumbnail <span>*</span></p>
    <input type="file" name="thumb" required accept="image/*" class="box">
    <p>select video <span>*</span></p>
    <input type="file" name="video" required accept="video/*" class="box">
    <input type="submit" value="add content" name="submit" class="btn">

    </form>

</section>













































<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>


</body>
</html>



