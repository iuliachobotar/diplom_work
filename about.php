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
            <h3>чому обирають нас?</h3>
            <p>Понад тисяча онлайн-курсів, десятки тисяч задоволених студентів і тисячі досвідчених викладачів — усе це, щоб ви могли зручно навчатися у своєму темпі.</p>
            <a href="courses.php" class="inline-btn">наші курси</a>
        </div>
    </div>

    <div class="box-container">

    <div class="box">
        <i class="fas fa-graduation-cap"></i>
        <div>
            <h3>+1k</h3>
            <span>онлайн курсів</span>
        </div>
    </div>

    <div class="box">
        <i class="fas fa-user-graduate"></i>
        <div>
            <h3>+25k</h3>
            <span>студентів-відмінників</span>
        </div>
    </div>

    <div class="box">
        <i class="fas fa-chalkboard-user"></i>
        <div>
            <h3>+5k</h3>
            <span>викладачів-експертів</span>
        </div>
    </div>

    <div class="box">
        <i class="fas fa-briefcase"></i>
        <div>
            <h3>100%</h3>
            <span>працевлаштування</span>
        </div>
    </div>

    </div>

</section>

<section class="reviews">

    <h1 class="heading">відгуки студентів</h1>

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
         <p>Навчання на платформі перевершило очікування! Уроки структуровані, викладачі завжди на зв’язку!</p>
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
         <p>Мені сподобалося, що можна вчитися у власному темпі. Відеоуроки якісні, з практичними прикладами, і нічого зайвого.</p>
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
         <p>Кожен викладач — справжній профі. Було цікаво слухати, не хотілося перемотувати! Рідко де зустрінеш таку подачу матеріалу.</p>
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
         <p>Я пройшов уже кілька курсів і щоразу вражений якістю. Навіть складні теми пояснюються на прикладах з реального життя.</p>
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
         <p>Я довго шукав курс, який не просто дає знання, а й допомагає зрозуміти, як їх застосовувати. Тут усе побудовано логічно та послідовно — вчуся із задоволенням.</p>
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
         <p>Особливо ціную підтримку з боку викладачів — завжди отримую швидкі відповіді на запитання, корисні поради та детальні пояснення.</p>
      </div>
   </div>

</section>






















<script src="js/script.js"></script>

</body>
</html>