<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
    
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="description" content="메인페이지"> -->
    <!-- <meta property="og:title" content="ego lego" /> -->
    <!-- <meta property="og:description" content="활동적인 아웃도어 라이프스타일" /> -->
    <title>Hello</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="js/common.js"></script>
    <!-- <script src="https://kit.fontawesome.com/8451689280.js" crossorigin="anonymous"></script> -->
</head>
<body>
    <!-- Header -->
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="nav-list">
                    <ul class="nav-menu">
                        <li><a href="#main">Home</a></li>
                        <li><a href="#projects">Projects</a></li>
                        <li><a href="#blog">Blog</a></li>
                        <!-- <li><a href="#about">About</a></li> -->
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                    <ul class="nav-manager" id="<?php echo $signinSessionStatus[2];?>">
                        <li class="manager-button">관리</li>
                        <ul class="manager-menu">
                            <li><a href="write_post.php">게시글 작성</a></li>
                            <li><a href="#">관리자페이지</a></li>
                            <li><a href="logout.php">로그아웃</a></li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header -->

    <!-- Main Section  -->
    <section id="main">
        <div class="main container">
            <div class="main-title">
                <h1>wellcome</h1>
                <h1>my portfolio!</h1>
                <!-- <h1></h1> -->
            </div>
        </div>
    </section>
    <!-- End Main Section  -->

    <!-- Projects Section -->
    <section id="projects">
        <div class="projects container">
            <div class="projects-header">
                <h1 class="section-title">프로젝트 영상 업로드</h1>
            </div>
        </div>
    </section>
    <!-- End Projects Section -->

    <!-- Blog Section -->
    <section id="blog">
        <div class="blog container">
            <a href="blog.php">블로그 전체 보기</a>
        </div>
    </section>
    <!-- End Blog Section  -->

    <!-- About Section -->
    <section id="about">
        <div class="about container">
            <!-- <div class="col-left">
                <div class="about-img">
                    <img src="./img/img-2.png" alt="img">
                </div>
            </div>
            <div class="col-right">
                <h1 class="section-title">About <span>me</span></h1>
                <h2>Front End Developer</h2>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Asperiores, velit alias eius non illum beatae atque repellat ratione qui veritatis repudiandae adipisci maiores. At inventore necessitatibus deserunt exercitationem cumque earum omnis ipsum rem accusantium quis, quas quia, accusamus provident suscipit magni! Expedita sint ad dolore, commodi labore nihil velit earum ducimus nulla quae nostrum fugit aut, deserunt reprehenderit libero enim!</p>
            </div> -->
        </div>
    </section>
    <!-- End About Section -->

    <!-- Contact Section -->
    <section id="contact">
        <div class="contact container">
            
        </div>
    </section>
    <!-- End Contact Section -->

</body>
</html>