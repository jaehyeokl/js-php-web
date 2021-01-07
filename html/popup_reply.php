<?php
    // 대댓글을 작성하기 위한 입력차이 있는 팝업창
    // 팝업창일 뿐 나머지는 게시글보기에서 댓글다는것과 동일

    if (!empty($_POST)) {
        // 게시글보기에서 답글달기를 눌러 팝업창이 떴을때
        // (POST 를 통해 데이터를 전달받았을때) 만 진행된다

        $postId = $_POST['postId'];
        $groupNum = $_POST['groupNum'];
        $targetUserName = $_POST['targetUserName'];
            
        // 답글의 대상 유저이름이 있을경우에는 textarea 에 '@대상이름' 형태의 텍스트를 입력해준다
        if ($targetUserName != null) {
            $targetUserName = "@".$targetUserName." ";
        } 

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
    <title>답글달기</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/popup_reply.css">
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="http://lonekorean.github.io/highlight-within-textarea/jquery.highlight-within-textarea.js"></script>
    <link rel="stylesheet" href="http://lonekorean.github.io/highlight-within-textarea/jquery.highlight-within-textarea.css"> -->
</head>
<body>
    <div class="comment_input">
        <form action="upload_comment.php" method="post">
            <h1>답글달기</h1>
            <input id="postId" name="postId" type="text" value="<?= $postId;?>">
            <input id="name" name="name" type="text" placeholder="Name" minlength="2" maxlength="12">
            <input id="password" name="password" type="text" placeholder="Password" minlength="4" maxlength="16">
            <textarea id="comment" name="comment" placeholder="Comment" minlength="2" maxliength="200"><?= $targetUserName;?></textarea>
            <input class="input_submit" type="submit" value="write">
            <input id="isNested" name="groupNum" type="text" value="<?= $groupNum;?>">
        </form>

        <!-- <script>
            $(function() {
                $('#comment').highlightWithinTextarea({
                    highlight: 'potato'

                    // highlight: /64 dollars?|\$6k billion?|7 million/gi,
                    // className: 'highlight'
                });
            });

            $('textarea').highlightWithinTextarea({
                // highlight: whatever // string, regexp, array, function, or custom object
                highlight: 'fdsaf'
            });
        </script> -->
    </div>
</body>