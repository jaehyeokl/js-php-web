<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
    $connectDB = connectDB(); // DB 연결

    // 새로 생성, 수정할 블로그 게시글 내용을 입력하는 페이지
    // 게시글 작성 일때 (default) url = www.ex.com/write_post.php
    // 게시글 수정 일때 url = www.ex.com/write_post.php?mode=modify&id=N(게시글 id)

    // 게시글이 수정상태인지 체크 (수정 true / 생성 false)
    $isModify = getStateModify();

    if ($isModify) {
        // 게시글 수정일때
        // 게시글을 수정할 수 있도록 기존에 작성된 내용을 input 태그에 반영해준다

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
    <link rel="stylesheet" href="css/write_post.css">
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
            <div class="upload_video">
                <button type="button">동영상 업로드</button>
                <input class="select_video" name= "video" type="file" accept="video/*">
            </div>
            <textarea name="contents_text" id="summernote"><?=$contentsText?></textarea>
            <input class="write_submit" type="submit" value="<?=$buttonName?>">
        </form>
    </section>

    <script>
        // Summernote Editor 설정
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
                onImageUpload : function(files, editor, welEditable) {
                    console.log('image upload:', files);
                    sendFile(files[0], editor, welEditable);
                }
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

        // ajax 를 이용하여 에디터에 업로드한 이미지(file)를 서버에 전달(POST) 한다
        // 서버 url(editor_realtime_upload.php)에서 임시폴더에 업로드된 파일을 저장한다 
        // 일단은 바로 임시폴더에 저장해놓았다가,   
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("image", file);
            
            $.ajax({
                url: "editor_realtime_upload.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                // url(save_board_image.php) 로 파일 전달이 완료됐을때
                // summernote 이미지 업로드 API 를 이용하여 서버에 '저장된' 이미지를 게시판에 입력
                success: function(data) {
                    // alert(data);
                    // API : $('#summernote').summernote('insertImage', url, filename);
                    // 에디터에 img 태그로 저장을 하기 위함
                    var image = $('<img>').attr('src', '' + data);
                    $('#summernote').summernote("insertNode", image[0]);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus+" "+errorThrown);
                }
            });
        }
    </script>

    <script>
        // 썸머노트에 비디오파일 올리기

        // input 태그를 통해 업로드할 파일을 선택했을때, 파일경로가 나타나게된다
        // 에디터에 업로드 할 때 여러 동영상 업로드가 가능하기 때문에, 경로를 나타나지 않도록 하려고 한다
        // 이를위해 기존 input 태그를 숨기고(hidden), 일반 Button 을 눌렀을때 숨겨놓은 input 을 클릭하도록 설정
        document.querySelector(".upload_video button").addEventListener("click", selectVideo);
        function selectVideo() {
            document.querySelector(".select_video").click();
        }

        // 파일 업로드 시 서버에 파일을 저장
        // input 태그에서 업로드할 파일을 선택했을때 발생하는 change 이벤트를 통해 서버에 파일을 업로드하는 메소드 실행
        document.querySelector(".select_video").addEventListener("change", sendVideo);

        function sendVideo() {
            fileVideo = new FormData(document.querySelector(".editor.form"));
            
            $.ajax({
                url: "editor_realtime_upload.php",
                data: fileVideo,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if (data === "") {
                        // alert(data);
                        alert("20M 이하의 동영상만 업로드 할 수 있습니다")
                    } else {
                        // 동영상태그에 컨트롤러, 너비 지정
                        var video = $('<video>').attr({'src':'' + data, 'controls':true, 'width':'800'});
                        $('#summernote').summernote("insertNode", video[0]);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus+" "+errorThrown);
                }
            });
        }

        // XMLHttpRequest 직접 구현하여 FormData 전달하였음,
        // 전달 완료 후 요청페이지에서 처리한 값을 다시 가져오는 것 구현하지 않고
        // jQuery 의 ajax를 사용하였음
        // function sendVideo() {
        //     var xhr = new XMLHttpRequest();
        //     var formData = new FormData(document.querySelector(".write-post"));
        
        //     xhr.onload = function() {
        //         if (xhr.status === 200 || xhr.status === 201) {
        //             // 요청 완료
        //             console.log(xhr.responseText);
        //             console.log("성공");
        //         } else {
        //             // 요청 실패
        //             console.error(xhr.responseText);
        //             console.log("실패");
        //         }
        //     };

        //     xhr.open('POST', 'http://54.180.215.159/phptest.php');
        //     xhr.send(formData);
        // }
    </script>

    <!-- TODO: 게시글 작성할때, 제목 길이제한, 게시글 길이제한 예외처리 해야한다  -->
    <!-- TODO: 게시글 등록 버튼 둥글게 -->
    <!-- TODO: 이미지업로드 또는 비디오 업로드일때 구현해야한다 -->
    <!-- TODO: 관리자 로그인일때 게시글 작성, 수정 버튼 보이도록 -->
    <!-- End Wirte Post -->
    
</body>    