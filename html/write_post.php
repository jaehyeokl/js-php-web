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
    <link rel="stylesheet" href="css/write_post.css">
    <!-- <script src="https://kit.fontawesome.com/8451689280.js" crossorigin="anonymous"></script> -->
</head>
<body>
    <!-- Header -->
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="nav-list">
                    <ul>
                        <li><a href="#main">Home</a></li>
                        <li><a href="#projects">Projects</a></li>
                        <li><a href="#blog">Blog</a></li>
                        <!-- <li><a href="#about">About</a></li> -->
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header -->

    <!-- Write Post -->
    <section id="editor">
        <div class="editor header">
            <a href="">BACK</a>
            <h1 class="editor-title">블로그 게시글 작성</h1>
        </div>
    
        <form class="editor form" action="upload_post.php" method="post">
            <input class="write_title"type="text" name="title" minlength="1" placeholder="제목을 작성해주세요" maxlength="45">
            <div class="write_video">
                <input class="select_video" name= "file" type="file" accept="video/*, image/*">
            </div>
            <textarea class="write_text" name="contents_text"></textarea>
            <input class="write_submit" type="submit" value="등록">
        </form>
    </section>
    <!-- TODO: 게시글 작성할때, 제목 길이제한, 게시글 길이제한 예외처리 해야한다  -->
    <!-- TODO: 이미지업로드 또는 비디오 업로드일때 구현해야한다 -->
    <!-- TODO: 관리자 로그인일때 게시글 작성, 수정 버튼 보이도록 -->
    <!-- End Wirte Post -->
    
</body>    