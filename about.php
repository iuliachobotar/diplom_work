<?php 
session_start();
include 'components/connect.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="about">

    <div class="row">
        <div class="image">
            <img src="images/about-img.svg" alt="">
        </div>

        <div class="content">
            <h3>why choose us?</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus asperiores dolorum ad praesentium, reiciendis sapiente voluptas assumenda, atque dolore quia sit adipisci laborum excepturi distinctio ipsum facilis quaerat eos a.</p>
            <a href="courses.php" class="inline-btn">our courses</a>
        </div>
    </div>

    <div class="box-container">

    <div class="box">
        <i class="fas fa-graduation-cap"></i>
        <div>
            <h3>+1k</h3>
            <span>online courses</span>
        </div>
    </div>

    <div class="box">
        <i class="fas fa-user-graduate"></i>
        <div>
            <h3>+25k</h3>
            <span>brilliants students</span>
        </div>
    </div>

    <div class="box">
        <i class="fas fa-chalkboard-user"></i>
        <div>
            <h3>+5k</h3>
            <span>expert teachers</span>
        </div>
    </div>

    <div class="box">
        <i class="fas fa-briefcase"></i>
        <div>
            <h3>100%</h3>
            <span>job placement</span>
        </div>
    </div>

    </div>

</section>

<section class="reviews">

    <h1 class="heading">student's reviews</h1>

    <div class="box-container">

      <div class="box">
         <div class="user">
            <img src="images/pic-02.png" alt="">
            <div>
               <h3>john deo</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illo fugiat, quaerat voluptate odio consectetur assumenda fugit maxime unde at ex?</p>
      </div>

      <div class="box">
         <div class="user">
            <img src="images/pic-03.png" alt="">
            <div>
               <h3>marry jain</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illo fugiat, quaerat voluptate odio consectetur assumenda fugit maxime unde at ex?</p>
      </div>

      <div class="box">
         <div class="user">
            <img src="images/pic-04.png" alt="">
            <div>
               <h3>joi swift</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illo fugiat, quaerat voluptate odio consectetur assumenda fugit maxime unde at ex?</p>
      </div>

      <div class="box">
         <div class="user">
            <img src="images/pic-09.png" alt="">
            <div>
               <h3>dark smitt</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illo fugiat, quaerat voluptate odio consectetur assumenda fugit maxime unde at ex?</p>
      </div>

      <div class="box">
         <div class="user">
            <img src="images/pic-10.png" alt="">
            <div>
               <h3>mark pollo</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illo fugiat, quaerat voluptate odio consectetur assumenda fugit maxime unde at ex?</p>
      </div>

      <div class="box">
         <div class="user">
            <img src="images/pic-07.png" alt="">
            <div>
               <h3>mao mao</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illo fugiat, quaerat voluptate odio consectetur assumenda fugit maxime unde at ex?</p>
      </div>
   </div>

</section>






















<script src="js/script.js"></script>

</body>
</html>