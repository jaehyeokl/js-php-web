<?php
    // TODO: 게시글 등록 수정할때 로그인 여부 확인

    // 새로 생성, 수정할 블로그 게시글 내용을 입력하는 페이지
    // 게시글 작성 일때 (default) url = www.ex.com/write_post.php
    // 게시글 수정 일때 url = www.ex.com/write_post.php?mode=modify&id=N(게시글 id)

    // 게시글이 수정상태인지 체크 (수정 true / 생성 false)
    $isModify = getStateModify();

    if ($isModify) {
        // 게시글 수정일때
        // 게시글을 수정할 수 있도록 기존에 작성된 내용을 input 태그에 반영해준다
        include_once("../resources/config.php");
        $connectDB = connectDB(); // DB 연결
        
        $postId = $_GET['id']; // 수정할 게시글 id

        $getPostStatement = $connectDB->prepare("SELECT * FROM blog WHERE id = :postId");
        $getPostStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $getPostStatement->execute();
            
        $postRow = $getPostStatement->fetch();
        // 제목과 작성중인 게시글을 반영한다
        $title = $postRow['title'];
        $contentsText = $postRow['contentsText'];
        // TODO: 저장된 이미지 또는 비디오파일도 반영해야함

    
        // 현재 작업을 알아차리기 쉽게 버튼 이름 지정
        $buttonName = "수정완료";
        // 수정완료 버튼을 통해 이동하는 upload_post.php 에 수정모드임을 전달해야한다. 
        // url 파라미터 추가하여 전달 ?mode=modify&id=N(수정할 게시글 id)
        $addModifyMode = "?mode=modify&id=".$postId;
    } else {
        $buttonName = "등록";
        $addModifyMode = "";
    }

    
    // 게시글 생성, 수정중 어떤 상태인지 확인하기
    function getStateModify() {
        $isModify = false;
        if (isset($_GET['mode'])) {
            if ($_GET['mode'] === "modify") {
                $isModify = true;
            }
        }
        return $isModify;
    }
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
    
        <form class="editor form" action="upload_post.php<?=$addModifyMode?>" enctype="multipart/form-data" method="post">
            <input class="write_title"type="text" name="title" minlength="1" value="<?=$title?>" placeholder="제목을 작성해주세요" maxlength="45">
            <div class="write_video">
                <input class="select_video" name= "contents_file" type="file" accept="video/*, image/*">
            </div>
            <textarea class="write_text" name="contents_text" placeholder="내용을 작성해주세요"><?=$contentsText?></textarea>
            <input class="write_submit" type="submit" value="<?=$buttonName?>">
        </form>
    </section>
    <!-- TODO: 게시글 작성할때, 제목 길이제한, 게시글 길이제한 예외처리 해야한다  -->
    <!-- TODO: 게시글 등록 버튼 둥글게 -->
    <!-- TODO: 이미지업로드 또는 비디오 업로드일때 구현해야한다 -->
    <!-- TODO: 관리자 로그인일때 게시글 작성, 수정 버튼 보이도록 -->
    <!-- End Wirte Post -->
    
</body>    