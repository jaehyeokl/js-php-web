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

    
    // 댓글 불러오기
    $getCommentStatement = $connectDB->prepare("SELECT * FROM comments WHERE postId = :postId AND deletedAt IS NULL AND nestedOrder = 0");
    $getCommentStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
    $getCommentStatement->execute();

    while($commentRow = $getCommentStatement->fetch()) {
        $name = $commentRow['name'];
        $createdAt = $commentRow['createdAt'];
        $comment = $commentRow['comment'];
        $groupNum = $commentRow['groupNum'];

        
        
        // 대댓글 불러오기
        $getNestedCommentStatement =$connectDB->prepare("SELECT * FROM comments WHERE postId = :postId AND deletedAt IS NULL AND groupNum = :groupNum AND nestedOrder != 0");
        $getNestedCommentStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $getNestedCommentStatement->bindParam(':groupNum', $groupNum, PDO::PARAM_INT);
        $getNestedCommentStatement->execute();

        $totalNestedCommentItemTag = "";
        
        while($nestedCommentRow = $getNestedCommentStatement->fetch()) {
            $nestedName = $nestedCommentRow['name'];
            $nestedCreatedAt = $nestedCommentRow['createdAt'];
            $nestedComment = $nestedCommentRow['comment'];

            $nestedOrder = $nestedCommentRow['nestedOrder'];

            $nestedCommentItemTag =
                                    "<div id='nested$nestedOrder' class='nested_comment_item'>".
                                        "<div class='comment_header'>".
                                            "<div class='header_left'>".
                                                "<span class='comment_writer'>$nestedName</span>".
                                                "<span class='comment_created'>$nestedCreatedAt</span>".
                                            "</div>".
                                            "<div class='header_right'>".
                                                "<span class='comment_reply'>reply</span>".
                                                "<span class='comment_edit'>edit</span>".
                                            "</div>".
                                        "</div>".
                                        "<textarea readonly>$nestedComment</textarea>".
                                    "</div>";
            
            $totalNestedCommentItemTag = $totalNestedCommentItemTag.$nestedCommentItemTag;
        }

        // 댓글 그룹 태그 생성 (일반 댓글 + 대댓글)
        $commentItemTag = $commentItemTag.
                                    "<div id='$groupNum' class='comment_item'>".
                                        "<div class='comment_header'>".
                                            "<div class='header_left'>".
                                                "<span class='comment_writer'>$name</span>".
                                                "<span class='comment_created'>$createdAt</span>".
                                            "</div>".
                                            "<div class='header_right'>".
                                                "<span class='comment_reply'>reply</span>".
                                                "<span class='comment_edit'>edit</span>".
                                            "</div>".
                                        "</div>".
                                        "<textarea readonly>$comment</textarea>".
                                        $totalNestedCommentItemTag. // 댓글 아래에 대댓글 추가
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
        <div class="viewer content">
            <!-- summernote 적용으로 인해  -->
            <!-- <div class="content_image">
                <?php //echo $imageTag;?>
            </div> -->
            <div class="content_text">
                <!-- <textarea readonly="readonly"><?=$contentsText?></textarea> -->
                <textarea name="contents_text" id="summernote"></textarea>
            </div>
        </div>
        <div class="viewer comment">
            <div class="comment_title">
                <h2>Comment</h2>
            </div>
            <div class="comment_list">
                <?= $commentItemTag ?>
            </div>
            <div class="comment_input">
                <form action="upload_comment.php" method="post">
                    <div class="input_userinfo">
                        <input id="name" name="name" type="text" placeholder="Name" minlength="2" maxlength="12">
                        <input id="password" name="password" type="text" placeholder="Password" minlength="4" maxlength="16">
                        <input id="postId" name="postId" type="text" value="<?= $postId;?>">
                    </div>
                    <textarea id="comment" name="comment" placeholder="Comment" minlength="2" maxliength="200"></textarea>
                    <input class="input_submit" type="submit" value="write">
                </form>
            </div>
        </div>
    </section>

    <script>
        // 댓글에 답글달기
        // 모든 답글버튼에서 클릭을 통해 답글달기가 진행될 수 있도록 설정
        let replyButtonArray = document.querySelectorAll(".comment_reply");

        for(let i = 0; i < replyButtonArray.length; i++) {
            replyButtonArray[i].addEventListener("click", function() {
                writeReply(this);
            });
        }

        function writeReply(el) {
            // 답글을 작성하는 팝업창을 띄운다
            // 작성한 답글을 DB에 저장할때 필요한 변수들을 POST 방식으로 팝업창에 전달한다

            // 전달할 변수 초기화
            // (게시글 번호, 댓글의 그룹번호, 답글 대상)
            let commentClassName = el.parentNode.parentNode.parentNode.getAttribute('class');
            let groupNum; // 댓글 그룹 번호
            let targetUserName = ""; // 지정 답글 유저 이름
            if (commentClassName === 'comment_item') {
                // 일반 댓글일 경우에는 태그의 id 값으로 댓글의 그룹번호를 가지고 있음
                groupNum = el.parentNode.parentNode.parentNode.getAttribute('id');
            } else if (commentClassName === 'nested_comment_item') {
                // 대댓글일 경우에는 부모태그인 일반댓글의 id를 통해 그룹번호를 확인해야한다
                groupNum = el.parentNode.parentNode.parentNode.parentNode.getAttribute('id');
                targetUserName = el.parentNode.parentNode.firstChild.firstChild.innerHTML;
            }

            let postId = <?= $postId;?>; // 게시글번호

            // 답글을 작성할 팝업창을 만들고, 초기화한 변수들을 전달한다 (POST)
            let popupStatus = "width=500, height=600, menubar=no, status=no, resizable=no";
            window.open("", "popupWriteReply", popupStatus); // 빈 popup 창

            // POST 전송하기위한 form 태그 초기화
            let form = document.createElement("form");
            form.setAttribute("charset", "UTF-8");
            form.setAttribute("method", "Post");
            form.setAttribute("target", "popupWriteReply"); // 만들어 놓은 팝업창
            form.setAttribute("action", "popup_reply.php"); // 팝업창에서 실행할 페이지
            // 전달할 변수 form 에 포함
            let hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "postId");
            hiddenField.setAttribute("value", postId);
            form.appendChild(hiddenField);

            hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "groupNum");
            hiddenField.setAttribute("value", groupNum);
            form.appendChild(hiddenField);

            hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "targetUserName");
            hiddenField.setAttribute("value", targetUserName);
            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>

<script>
        // 댓글 수정 및 삭제
        // 모든 수정 버튼에서 클릭을 통해 댓글수정 진행될 수 있도록 설정
        let editButtonArray = document.querySelectorAll(".comment_edit");

        for(let i = 0; i < editButtonArray.length; i++) {
            editButtonArray[i].addEventListener("click", function() {
                editComment(this);
            });
        }

        function editComment(el) {
            // 팝업창에서 댓글 비밀번호를 확인하고 수정/삭제를 한다
            // 수정 또는 삭제될때 데이터를 전달하여 DB에 반영한다

            // 전달할 변수 초기화
            // (게시글 번호, 댓글의 그룹번호, 답글 대상)
            let commentClassName = el.parentNode.parentNode.parentNode.getAttribute('class');
            let groupNum; // 댓글 그룹 번호
            let nestedOrder = 0;
            if (commentClassName === 'comment_item') {
                // 일반 댓글일 경우에는 태그의 id 값으로 댓글의 그룹번호를 가지고 있음
                groupNum = el.parentNode.parentNode.parentNode.getAttribute('id');
            } else if (commentClassName === 'nested_comment_item') {
                // 대댓글일 경우에는 부모태그인 일반댓글의 id를 통해 그룹번호를 확인해야한다
                groupNum = el.parentNode.parentNode.parentNode.parentNode.getAttribute('id');
                nestedOrder = el.parentNode.parentNode.parentNode.getAttribute('id');
                // id=nested1 의 형태로 id값이 지정되어 있다 여기서 숫자만 가져온다
                nestedOrder.replace('nested', '');
            }

            let postId = <?= $postId;?>; // 게시글번호
    
            // 답글을 작성할 팝업창을 만들고, 초기화한 변수들을 전달한다 (POST)
            let popupStatus = "width=500, height=600, menubar=no, status=no, resizable=no";
            window.open("", "popupEditReply", popupStatus); // 빈 popup 창

            // POST 전송하기위한 form 태그 초기화
            let form = document.createElement("form");
            form.setAttribute("charset", "UTF-8");
            form.setAttribute("method", "Post");
            form.setAttribute("target", "popupEditReply"); // 만들어 놓은 팝업창
            form.setAttribute("action", "popup_edit_reply.php"); // 팝업창에서 실행할 페이지
            // 전달할 변수 form 에 포함
            let hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "postId");
            hiddenField.setAttribute("value", postId);
            form.appendChild(hiddenField);

            hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "groupNum");
            hiddenField.setAttribute("value", groupNum);
            form.appendChild(hiddenField);

            hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "nestedOrder");
            hiddenField.setAttribute("value", nestedOrder);
            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>

    <script>
        // ajax 를 이용하여 서버 upload_comment.php 에서 DB에 저장할 댓글 데이터 전달
        // document.querySelector(".input_submit").addEventListener("click", uploadComment);

        // function uploadComment() {
        //     // 전달할 데이터
        //     let commentDataArray = new Array();
        //     let commentData = new Object();

        //     commentData.postId = <?= $postId;?>;
        //     commentData.name = document.querySelector("#name").value;
        //     commentData.password = document.querySelector("#password").value;
        //     commentData.comment = document.querySelector("#comment").value;
        //     // commentData.createdAt = Date.now();
            
        //     commentDataArray.push(commentData);
            
        //     $.ajax({
        //         url: "upload_comment.php",
        //         dataType: "json",
        //         data: {"data" : commentDataArray},
        //         type: "POST",
        //         success: function(data) {
        //             // 현재 스크롤 위치로 새로고침
        //             // alert(data);
        //             console.log(data);
        //             // document.location.reload(true);
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log(textStatus+" "+errorThrown);
        //         }
        //     });
        // }
    </script>

    <script>
        // 댓글을 담는 textarea 높이 조절
        // 불러오는 댓글의 양만큼 textarea 의 높이를 자동으로 조절하도록 한다
        function textareaAutoHeight() {
            let commentTextareaArray = document.querySelectorAll(".comment_item textarea");

            for (let i = 0; i < commentTextareaArray.length; i++) {
                let el = commentTextareaArray[i];
                setTimeout(() => {
                    el.style.height = 'auto';

                    let scrollHeight = el.scrollHeight;
                    let outlineHeight = el.offsetHeight - el.clientHeight;

                    el.style.height = (scrollHeight + outlineHeight) + 'px';
                }, 0);
            }
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

    <!-- Footer Section -->
    <!-- <section id="footer">
        <div class="footer">
            <a href="signin.php">관리자 로그인</a>
            <span>@Designed By JaeHyeok</span>
        </div>
    </section> -->
    <!-- End Footer Section -->
</body>