<?php
session_start();
include '../components/connect.php';

if (isset($_SESSION['tutor_id'])) {
    $tutor_id = $_SESSION['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:playlists.php');
    exit();
}

if (isset($_POST['update'])) {
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $playlist_id = filter_var($_POST['playlist_id'], FILTER_SANITIZE_STRING);

    // Оновлення контенту в базі

    $update_content = $conn->prepare("UPDATE `content` SET title = ?, description = ?, status = ?, playlist_id = ? WHERE id = ? AND tutor_id = ? LIMIT 1");
    $update_content->execute([$title, $description, $status, $playlist_id, $get_id, $tutor_id]);


    if (!empty($playlist_id)) {
        $update_playlist = $conn->prepare("UPDATE `content` SET playlist_id = ? WHERE id = ?");
        $update_playlist->execute([$playlist_id, $get_id]);
    }

    // Оновлення мініатюри
    $old_thumb = $_POST['old_thumb'] ?? '';
    $old_thumb = filter_var($old_thumb, FILTER_SANITIZE_STRING);
    $thumb = $_FILES['thumb']['name'] ?? '';
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename_thumb = 'playlist_thumb_' . uniqid('', true) . '.' . $thumb_ext;
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'] ?? '';
    $thumb_size = $_FILES['thumb']['size'] ?? 0;
    $thumb_folder = '../uploaded_files/' . $rename_thumb;
    
    if (!empty($thumb)) {
        if ($thumb_size > 2000000) {
            $message[] = 'image size is too large!';
        } else {
            $update_thumb = $conn->prepare("UPDATE `content` SET thumb = ? WHERE id = ? ");
            $update_thumb->execute([$rename_thumb, $get_id]);

            if (move_uploaded_file($thumb_tmp_name, $thumb_folder)) {
                if ($old_thumb != '' && file_exists('../uploaded_files/' . $old_thumb)) {
                    unlink('../uploaded_files/' . $old_thumb);
                }
            } else {
                die('Error: Failed to move uploaded file');
            }
        }
    }

    // Оновлення відео
    $old_video = $_POST['old_video'];
    $old_video = filter_var($old_video, FILTER_SANITIZE_STRING);
    $video = $_FILES['video']['name'];
    $video = filter_var($video, FILTER_SANITIZE_STRING);
    $video_ext = pathinfo($video, PATHINFO_EXTENSION);
    $rename_video = 'playlist_video_' . uniqid('', true) . '.' . $video_ext;
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = '../uploaded_files/' . $rename_video;

    if (!empty($video)) {
        $update_video = $conn->prepare("UPDATE `content` SET video = ? WHERE id = ?");
        $update_video->execute([$rename_video, $get_id]);
        move_uploaded_file($video_tmp_name, $video_folder);
        if ($old_video != '') {
            unlink('../uploaded_files/' . $old_video);
        }
    }

    $message[] = 'content updated!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Content</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="crud-form">

    <h1 class="heading">update content</h1>

    <?php
    // Замість вибору всього контенту, вибираємо тільки той, що має id, передане в URL
    $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
    $select_content->execute([$get_id]);
    
    if ($select_content->rowCount() > 0) {
        $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>">
        <input type="hidden" name="old_video" value="<?= $fetch_content['video']; ?>">
        <input type="hidden" name="old_thumb" value="<?= $fetch_content['thumb']; ?>">

        <p>content status </p>
        <select name="status" required class="box">
            <option value="active" <?= $fetch_content['status'] == 'active' ? 'selected' : ''; ?>>active</option>
            <option value="deactive" <?= $fetch_content['status'] == 'deactive' ? 'selected' : ''; ?>>deactive</option>
        </select>

        <p>content title </p>
        <input type="text" class="box" name="title" maxlength="100" placeholder="enter content title" value="<?= $fetch_content['title']; ?>">

        <p>content description </p>
        <textarea name="description" class="box" cols="30" required placeholder="enter content description" maxlength="1000" rows="10"><?= $fetch_content['description']; ?></textarea>

        <select name="playlist_id" class="box">
            <option value="<?= $fetch_content['playlist_id']; ?>" selected>--select playlist</option>
            <?php 
            $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
            $select_playlist->execute([$tutor_id]);
            if ($select_playlist->rowCount() > 0) {
                while ($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <option value="<?= $fetch_playlist['id']; ?>" <?= $fetch_playlist['id'] == $fetch_content['playlist_id'] ? 'selected' : ''; ?>><?= $fetch_playlist['title']; ?></option>
            <?php 
                }
            } else {
                echo '<option value="" disabled>no playlist created yet!</option>';
            }
            ?>
        </select>

        <p>update thumbnail </p>
        <img src="../uploaded_files/<?= $fetch_content['thumb']; ?>" alt="thumbnail">
        <input type="file" name="thumb" accept="image/*" class="box">

        <p>update video </p>
        <video src="../uploaded_files/<?= $fetch_content['video']; ?>" class="media" controls></video>
        <input type="file" name="video" accept="video/*" class="box">

        <input type="submit" value="update content" name="update" class="btn">
        <div class="flex-btn">
            <a href="view_content.php?get_id=<?= $get_id; ?>" class="option-btn">view content</a>
            <input type="submit" value="delete content" name="delete_content" class="delete-btn">
        </div>
    </form>
    <?php
    } else {
        echo '<p class="empty">content was not found!</p>';
    }
    ?>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>