<?php
    // 게시글 불러오기
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
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
    // $contentsImageId = $postRow['contentsImageId'];
    $createdAt = $postRow['createdAt'];

    // summernote 적용으로 인해 이미지를 DB에서 불러오지 않고, 본문의 img 태그에서 파일로 불러온다
    // if ($contentsImageId) {
    //     $getImageStatement = $connectDB->prepare("SELECT image FROM image WHERE imageId = :imageId");
    //     $getImageStatement->bindParam(':imageId', $contentsImageId, PDO::PARAM_INT);
    //     $getImageStatement->execute();

    //     $getImageStatement->setFetchMode(PDO::FETCH_ASSOC);
    //     $getImageResult = $getImageStatement->fetch();
    //     // header("Content-type: image/jpeg");
    //     $imageBlob = $getImageResult['image'];
    //     // 이미지 blob img 태그에 넣기
    //     // https://stackoverflow.com/questions/20556773/php-display-image-blob-from-mysql
    //     $imageTag = '<img src="data:image/jpeg;base64,'.base64_encode($imageBlob).'"/>';
    // }
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
    <script src="js/common.js"></script>
    <!-- <script src="https://kit.fontawesome.com/8451689280.js" crossorigin="anonymous"></script> -->
    <!-- summernote 사용 위한 bootstrap, jquery -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.js"></script>
</head>
<body>
    <!-- Header -->
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="nav-list">
                    <ul class="nav-menu">
                        <li><a href="index.php">Home</a></li>
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
                    <span class="edit-button">＜</span>
                </div>
            </div>
        </div>
        <div class="viewer body">
            <!-- summernote 적용으로 인해  -->
            <!-- <div class="content_image">
                <?php //echo $imageTag;?>
            </div> -->
            <div class="content_text">
                <!-- <textarea readonly="readonly"><?=$contentsText?></textarea> -->
                <textarea name="contents_text" id="summernote"></textarea>
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
    </script>

    <script>
        // summernote 설정
        $('#summernote').summernote({
            airMode: true,
            height : 400,
            // maxHeight : 400,
            minHeight : 400,
            // tabsize: 2,
            focus : true,
            lang : 'ko-KR',
            // 드래그 드롭을 통해 게시글에 내용 추가되는것 확인하였음
            // 읽기전용이기 때문에 해당 기능 비활성화
            disableDragAndDrop: true,
            // 읽기전용 모든 툴바를 제거한다
            toolbar: [
                // ['style', ['style']],
                // ['font', ['bold', 'underline', 'clear']],
                // ['fontname', ['fontname']],
                // ['color', ['color']],
                // ['para', ['ul', 'ol', 'paragraph']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                // ['view', ['fullscreen', 'codeview', 'help']],
            ]
        });

        // 저장된 게시글 적용
        var contents = '<?=$contentsText?>';
        $('#summernote').summernote('pasteHTML', contents);

        // 읽기 전용, 쓰기 기능 비활성화
        $('#summernote').summernote('disable');
    </script> 
    <!-- End View Post -->
</body>