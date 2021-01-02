<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
    $connectDB = connectDB(); // DB 연결

    // Blog 게시글 미리보기
    $previewPostNum = 6; // 보여줄 미리보기 포스트 개수

    $previewPostStatement = $connectDB->prepare("SELECT id, title, thumbnail FROM blog WHERE deletedAt IS NULL ORDER BY id DESC LIMIT :viewPostNum");
    $previewPostStatement->bindParam(':viewPostNum', $previewPostNum, PDO::PARAM_INT);
    $previewPostStatement->execute();

    while ($previewPostRow = $previewPostStatement->fetch()) {
        $postId = $previewPostRow['id'];
        $postTitle = $previewPostRow['title'];
        $postThumnail = $previewPostRow['thumbnail'];

        // 블로그 게시글 태그 생성
        $previewPostTag = $previewPostTag.
                                    "<div class='post'>".
                                        "<a href='view_post.php?id=$postId'>".
                                            "<img src='$postThumnail' onerror=this.style.visibility='hidden'>".
                                            "<h3>".$postTitle."</h3>".
                                        "</a>".
                                    "</div>";
    }

    $connectDB = null;
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
            <div class="blog-header">
                <h1>BLOG</h1>
                <a href="blog.php">more post</a>
            </div>
            <div class="blog-body">
                <?= $previewPostTag ?>
            </div>
        </div>
    </section>
    <!-- End Blog Section  -->

    <!-- Contact Section -->
    <section id="contact">
        <div class="contact container">
            <div class="contact-title">
                <h1>CONTACT ME</h1>
            </div>
            <div class="contact-form">
                <form action="contact_message.php" method="post">
                    <input class="input" type="text" name="name" placeholder="Name" maxlength="20" minlength="2">
                    <input class="input" type="text" name="email" placeholder="Email" maxlength="45" minlength="10">
                    <textarea class="input" name="message" id=""  placeholder="Message" maxlength="3000" minlength="1"></textarea>
                    <input class="submit" type="submit" value="SUBMIT">
                </form>
            </div>
        </div>
    </section>
    <!-- End Contact Section -->


    <!-- About Section -->
    <!-- <section id="about">
        <div class="about container"> -->
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
        <!-- </div>
    </section> -->
    <!-- End About Section -->

    
</body>
</html>