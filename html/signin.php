<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
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
    <link rel="stylesheet" href="css/signin.css">
    <script src="js/common.js"></script>
    <!-- <script src="https://kit.fontawesome.com/8451689280.js" crossorigin="anonymous"></script> -->
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
                        <ul>
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

    <!-- Sign in Section  -->
    <section id="signin">
        <div class="signin header">
            <h1>로그인</h1>
        </div>
        <div class="signin body">
            <form class="form" action="signin_server.php" method="post">
                <span class="form__text">email</span>
                <input class="form__input email" name="email" type="email" maxlength="40">
                <span class="form__text email-rule" id="testid">올바른 이메일을 입력해주세요</span>
                <span class="form__text">password</span>
                <input class="form__input password" name="password" type="password" minlength="8" maxlength="20">
                <!-- <a class="form__button_forgot" type="button" href="#">비밀번호를 잊으셨나요?</a> -->
                <button name="submit" type="button" class="form__button" id="submit">로그인</button>
            </form>
        </div>
    </section>
    <!-- End Sign in Section  -->

    <script>
        // 올바른 이메일 형식 / 비밀번호 길이 확인

        // 정규표현식을통한 이메일 형식 체크 (ex email@naver.com)
        // 이메일 양식과 일치하지 않을 경우 숨겨져있는 안내메세지를 보여준다
        let emailRule = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
        let inputEmail = document.querySelector('.form__input.email');
        inputEmail.addEventListener("keyup", checkEmail);

        function checkEmail() {
            const email = inputEmail.value;
            const checkRule = emailRule.test(email)
                
            if (email.length > 0) {
                if (checkRule==true) {
                    document.querySelector('.email-rule').style.visibility = "hidden";
                    return true;
                } else {
                    document.querySelector('.email-rule').style.visibility = "visible";
                    return false;
                }
            } else {
                document.querySelector('.email-rule').style.visibility = "hidden";
            }
        }
            
        // 올바른 이메일 양식, 비밀번호 길이를 입력했을때, 로그인 버튼을 활성화한다
        const submitButton = document.querySelector('#submit');
        submitButton.addEventListener("click", checkInput);
        function checkInput() {
            const statusEmail = checkEmail();
            const passwordLength = document.querySelector('.form__input.password').value.length;

            if (statusEmail == true && passwordLength >= 8) {
                submitButton.type = "submit";
            }
        }
    </script>
</html>