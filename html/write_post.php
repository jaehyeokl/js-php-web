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
                    <ul>
                        <li><a href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header -->

    <!-- Write Post -->
    <section id="editor">
        <div class="editor header">
            <a href="blog.php">BACK</a>
            <h1 class="editor-title">블로그 게시글 작성</h1>
        </div>
    
        <form class="editor form" action="upload_post.php<?=$addModifyMode?>" enctype="multipart/form-data" method="post">
            <input class="write_title"type="text" name="title" minlength="1" value="<?=$title?>" placeholder="제목을 작성해주세요" maxlength="45">
            <div class="write_video">
                <input class="select_video" name= "contents_file" type="file" accept="video/*, image/*">
            </div>
            <!-- <textarea class="write_text" name="contents_text" placeholder="내용을 작성해주세요"><?=$contentsText?></textarea> -->
            <textarea name="" id="summernote" cols="30" rows="10"><?=$contentsText?></textarea>
            <input class="write_submit" type="submit" value="<?=$buttonName?>">
        </form>
    </section>

    <script>
         $('#summernote').summernote({
            // height : 400,
            // maxHeight : 400,
            minHeight : 400,
            focus : true,
            lang : 'ko-KR',
            callbacks: {
                // 업로드한 이미지를 Base 64 인코딩 형태가 아닌 파일자체를 서버에 저장하기 위해서
                // 이미지 업로드 직후에 작동하는 callback 메소드인 onImageUpload 에  
                // 서버에 이미지를 파일로 저장하는 메소드(sendFile)을 override 한다
                // onImageUpload : function(files, editor, welEditable) {
                //     console.log('image upload:', files);
                //     sendFile(files[0], editor, welEditable);
                // }
            },

            // 툴바에 들어갈 들어갈 기능 설정
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                // ['para', ['ul', 'ol', 'paragraph']],
                // ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                // ['view', ['fullscreen', 'codeview', 'help']],
            ],

            popover: {
                image: [
                    // 첨부한 이미지 리사이즈 금지하기 위한 설정
                    // ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                    // ['float', ['floatLeft', 'floatRight', 'floatNone']],
                    // ['remove', ['removeMedia']]
                ],
                link: [
                    ['link', ['linkDialogShow', 'unlink']]
                ],
                table: [
                    ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                    ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
                ],
                air: [
                    ['color', ['color']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']]
                ]
            }
        });
    </script>

    <!-- TODO: 게시글 작성할때, 제목 길이제한, 게시글 길이제한 예외처리 해야한다  -->
    <!-- TODO: 게시글 등록 버튼 둥글게 -->
    <!-- TODO: 이미지업로드 또는 비디오 업로드일때 구현해야한다 -->
    <!-- TODO: 관리자 로그인일때 게시글 작성, 수정 버튼 보이도록 -->
    <!-- End Wirte Post -->
    
</body>    