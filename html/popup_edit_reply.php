<?php
    // 댓글을 수정하기위한 팝업창

    if (!empty($_POST)) {

        include_once("../resources/config.php");
        $connectDB = connectDB(); // DB 연결
       
        $postId = $_POST['postId'];
        $groupNum = $_POST['groupNum'];
        $nestedOrder = $_POST['nestedOrder'];
        
        // 데이터베이스 comments 테이블에서 수정할 댓글 row 불러오기
        $getCommentStatement = $connectDB->prepare("SELECT * FROM comments WHERE postId = :postId AND groupNum = :groupNum AND nestedOrder = :nestedOrder");
        $getCommentStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $getCommentStatement->bindParam(':groupNum', $groupNum, PDO::PARAM_INT);
        $getCommentStatement->bindParam(':nestedOrder', $nestedOrder, PDO::PARAM_INT);
        $getCommentStatement->execute();

        // 해당 row 에 저장된 비밀번호, 작성된 글, 작성이름 초기화
        // 해당 변수들을 javascript 에서 사용할 예정
        $commentRow = $getCommentStatement->fetch();
        $name = $commentRow['name'];
        $password = $commentRow['password'];
        $comment = $commentRow['comment'];

    } else {
        // 정상적인 경로가 아닙니다
        echo "<script>
                alert('정상적인 경로가 아닙니다');
                location.replace('https://jaehyeok.ml');
            </script>";
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
    <title>답글수정</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/popup_reply.css">
</head>
<body>
    <div class="edit_comment">
        <h1>게시글 수정 / 삭제</h1>
        <div class="select_state">
            <button class="state_modify">수정</button>
            <button class="state_delete">삭제</button>
        </div>
        <div class="check_password">
            <input class="input_password" name="password" type="text" placeholder="게시글 비밀번호를 입력해주세요" minlength="4" maxlength="16">
            <input class="check" type="button" value="확인">
        </div>
        <form action="edit_comment.php" method="post">
            <input class="input_delete" type="submit" value="삭제">
            <input class="name" name="name" type="text" placeholder="Name" minlength="2" maxlength="12">
            <textarea class="comment" name="comment" placeholder="Comment" minlength="2" maxliength="200"></textarea>
            <input class="input_submit" type="submit" value="수정">
            <input class="postId" name="postId" type="text" value="<?= $postId;?>">
            <input class="groupNum" name="groupNum" type="text" value="<?= $groupNum;?>">
            <input class="nestedOrder" name="nestedOrder" type="text" value="<?= $nestedOrder;?>">
            <input class="state" name="state" type="text" value="0">
        </form>
    </div>

    <script>
        // 수정/삭제 선택
        // form 태그의 action 에서 작업할 내용이 수정인지 삭제인지 알려주기 위한 input 태그 값 설정
        // 0일때 수정, 1일때 삭제
        let modifyButton =  document.querySelector(".state_modify");
        let deleteButton = document.querySelector(".state_delete");
        modifyButton.addEventListener("click", function() {
            document.querySelector(".state").value = 0;
            modifyButton.id = "selected";
            deleteButton.id = "";
        });

        deleteButton.addEventListener("click", function() {
            document.querySelector(".state").value = 1;
            modifyButton.id = "";
            deleteButton.id = "selected";
        });


        // 비밀번호 체크
        document.querySelector(".check").addEventListener("click", function() {
            let password = <?= $password;?>; // DB에서 가져온 해당 댓글의 비밀번호
            let inputPassword = document.querySelector(".input_password").value;
            
            // 댓글을 수정/삭제할 수 있는 비밀번호 체크
            if (password == inputPassword) {
                let state = document.querySelector(".state").value;
                
                if (state == 0) {
                    // 게시글 수정일때
                    // 숨겨진 form 태그를 보이게하여 댓글을 수정할 수 있도록 한다
                    document.querySelector(".check_password").style.display = "none";
                    document.querySelector("form").style.visibility = "visible";

                    // 수정할 수 있도록 기존에 작성된 내용을 input 창에 보여준다
                    document.querySelector(".name").value = "<?= $name;?>";
                    document.querySelector(".comment").value = "<?= $comment;?>";

                } else if (state == 1) {
                    // 게시글 삭제일때
                    // 숨겨진 form 태그에서 삭제 버튼만 보여준다
                    document.querySelector(".check_password").style.display = "none";
                    document.querySelector(".input_delete").style.visibility = "visible";
                }

            } else {
                alert("비밀번호가 일치하지 않습니다");
            }
        });
    </script>
</body>