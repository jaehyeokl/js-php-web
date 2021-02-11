<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
    $connectDB = connectDB(); // DB 연결
    logVisitor(); // 방문 로그 체크 및 저장

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
    <meta name="description" content="Home">
    <meta property="og:title" content="JaeHyeok's Portfolio" />
    <meta property="og:description" content="개발자 이재혁 포트폴리오 사이트" />
    <meta name="keywords" content="개발자 포트폴리오" />
    <title>Home</title>
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
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="live.php">Live</a></li>
                    </ul>
                    <ul class="nav-manager" id="<?php echo $signinSessionStatus[2];?>">
                        <li class="manager-button">관리</li>
                        <ul class="manager-menu">
                            <li><a href="write_post.php">게시글 작성</a></li>
                            <li><a href="manager.php">관리자페이지</a></li>
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
                <h1>PORTFOLIO</h1>
                <span>by JAE HYEOK</span>
            </div>
        </div>
    </section>
    <!-- End Main Section  -->

    <!-- Projects Section -->
    <section id="projects">
        <div class="projects container">
            <div class="projects-header">
                <div class="header-title">
                    <h1>PROJECTS</h1>
                </div>
                <div class="header-menu">
                    <span id="talkpic">TALK PIC</span>
                    <span id="ggym">GGYM</span>
                    <span id="mafia">MAFIA</span>
                </div>
            </div>
            <div class="projects-body">
                <video src="/video/project/android_talkpic.mp4" controls width="100%"></video>
            </div>
        </div>
    </section>

    <script>
        // 프로젝트 선택에 따라 해당 동영상 불러오기
        let projectFileName = ["android_talkpic.mp4", "android_ggym.mp4", "java_mafia.mp4"];
        let projectVideoTag = document.querySelector(".projects-body video"); // 프로젝트 보여줄 비디오 태그
        let projectButtons = document.querySelectorAll(".header-menu span");
        for (let i = 0; i < projectButtons.length; i++) {
            projectButtons[i].addEventListener("click",() => {
                // 비디오태그에 선택된 동영상 src 변경
                projectVideoTag.src = "/video/project/" + projectFileName[i];

                // 선택된 버튼 CSS 처리하기 (class="clicked" 추가)
                // 이전에 선택된 버튼이 있다면 클래스명을 제거하고, 현재 선택된 버튼만 클래스명 추가
                for (let i = 0; i < projectButtons.length; i++) {
                    projectButtons[i].classList.remove("clicked");
                }

                projectButtons[i].classList.toggle("clicked");
            });
        }
    </script>
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

    <!-- Footer Section -->
    <section id="footer">
        <div class="footer">
            <a href="signin.php">관리자 로그인</a>
            <span>@Designed By JaeHyeok</span>
        </div>
    </section>
    <!-- End Footer Section -->


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