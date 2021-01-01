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
    <link rel="stylesheet" href="css/signin.css">
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
    </section>
    <!-- End Header -->

    <!-- Sign in Section  -->
    <section id="signin">
        <div class="signin header">
            <h1>로그인</h1>
        </div>
        <div class="signin body">
            <form class="form" action="signin.php" method="post">
                <span class="form__text">email</span>
                <input class="form__input" name="email" type="email" maxlength="40" id="email">
                <span class="form__text email-rule" id="testid">올바른 이메일을 입력해주세요</span>
                <span class="form__text">password</span>
                <input name="password" type="password" minlength="8" maxlength="20" class="form__input" id="password">
                <!-- <a class="form__button_forgot" type="button" href="#">비밀번호를 잊으셨나요?</a> -->
                <button name="submit" type="button" class="form__button" id="submit">로그인</button>
            </form>
        </div>
    </section>
    <!-- End Sign in Section  -->
</html>