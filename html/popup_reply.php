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
    <script src="js/common.js"></script>
</head>
<body>
    <div class="comment_input">
        <form action="upload_comment.php" method="post">
            <div class="input_userinfo">
                <input id="name" name="name" type="text" placeholder="Name" minlength="2" maxlength="12">
                <input id="password" name="password" type="text" placeholder="Password" minlength="4" maxlength="16">
                <input id="postId" name="postId" type="text" value="<?= $postId;?>">
            </div>
            <textarea id="comment" name="comment" placeholder="Comment" minlength="2" maxliength="200"></textarea>
            <input class="input_submit" type="submit" value="write">
            <!-- <input class="input_submit" type="button" value="write"> -->
        </form>
    </div>
</body>