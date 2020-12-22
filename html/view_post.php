<?php
    // 게시글 불러오기
    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결
    
    $postId = $_GET['id']; // 게시글 id

    // TODO: 작성자 불러올경우에는 JOIN 을 통하여 한번의 쿼리문으로 함께 불러올 수 있도록 하자
    // TODO: 수정일을 보여줄 수 있는 방법을 생각해보자
    $getPostStatement = $connectDB->prepare("SELECT * FROM blog WHERE id = :postId");
    $getPostStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
    $getPostStatement->execute();
        
    $postRow = $getPostStatement->fetch();
    // $writerId = $row['creater'];
    $title = $postRow['title'];
    $contentsText = $postRow['contentsText'];
    $contentsImageId = $postRow['contentsImageId'];
    $createdAt = $postRow['createdAt'];

    if ($contentsImageId) {
        $getImageStatement = $connectDB->prepare("SELECT image FROM image WHERE imageId = :imageId");
        $getImageStatement->bindParam(':imageId', $contentsImageId, PDO::PARAM_INT);
        $getImageStatement->execute();

        $getImageStatement->setFetchMode(PDO::FETCH_ASSOC);
        $getImageResult = $getImageStatement->fetch();
        // header("Content-type: image/jpeg");
        $imageBlob = $getImageResult['image'];
        // 이미지 blob img 태그에 넣기
        // https://stackoverflow.com/questions/20556773/php-display-image-blob-from-mysql
        $imageTag = '<img src="data:image/jpeg;base64,'.base64_encode($imageBlob).'"/>';
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
    <link rel="stylesheet" href="css/view_post.css">
    <!-- <script src="https://kit.fontawesome.com/8451689280.js" crossorigin="anonymous"></script> -->
</head>
<body>
    <!-- Header -->
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="nav-list">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="blog-title">
            <a href="blog.php">BLOG</a>
        </div>
    </section>
    <!-- End Header --> 

    <!-- View Post -->
    <section id="viewer">
        <div class="viewer header">
            <div class="header_top">
                <h1 class="post_title"><?=$title?></h1>
            </div>
            <div class="header_bottom">
                <div class="post_information">
                    <span class="created-at">작성일 : <?=$createdAt?></span>
                </div>
                <div class="post_button">
                    <a href="write_post.php?mode=modify&id=<?=$postId?>">수정</a>
                    <a href="upload_post.php?mode=delete&id=<?=$postId?>">삭제</a>
                    <a class="edit-button">＜</a>
                </div>
            </div>
        </div>
        <div class="viewer body">
            <div class="content_image">
                <?php echo $imageTag;?>
            </div>
            <div class="content_text">
                <textarea readonly="readonly"><?=$contentsText?></textarea>        
            </div>
        </div>
        <div class="viewer footer">
            <span>댓글 다는곳</span>
            <!-- TODO: Comment 구현하기 -->
        </div>
    </section>

    <script>
        // 불러오는 게시글의 양만큼 textarea 의 높이를 자동으로 조절하도록 한다
        function textareaAutoHeight() {
            let el = document.querySelector(".content_text textarea");
            setTimeout(() => {
                el.style.height = 'auto';

                let scrollHeight = el.scrollHeight;
                let outlineHeight = el.offsetHeight - el.clientHeight;

                el.style.height = (scrollHeight + outlineHeight) + 'px';
            }, 0);
        }
        textareaAutoHeight();
        // TODO: 브라우저 창의 크기가 바뀔때마다 새로 실행디되도록 하기

        // 게시글 수정/ 삭제
    </script>
    <!-- End View Post -->
</body>