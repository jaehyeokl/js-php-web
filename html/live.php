<?php 
    // 라이브 스트리밍 페이지
    // 이메일 인증을 하면 라이브 스트리밍을 바로 시청할 수 있다

    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
    $connectDB = connectDB(); // DB 연결
    logVisitor(); // 방문 로그 체크 및 저장
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="description" content="메인페이지"> -->
    <!-- <meta property="og:title" content="ego lego" /> -->
    <!-- <meta property="og:description" content="활동적인 아웃도어 라이프스타일" /> -->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    <title>LIVE</title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/live.css">
    <script src="js/common.js"></script>
    <!-- video.js CSS -->
    <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" /> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/6.12.0/video.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dashjs/3.2.0/dash.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-dash/2.11.0/videojs-dash.min.js"></script>
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
                            <li><a href="manager.php">관리자페이지</a></li>
                            <li><a href="logout.php">로그아웃</a></li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header -->
    
    <!-- Live Section -->
    <section id="live">
        <div class="live-left">
            <div class="left-header">
                <div class="header-title">
                    <h1>Live Streaming</h1>
                </div>
                <div class="header-auth">
                    <div class="auth-user certified">
                        <span>USER</span>
                        <span class="certified-email"></span>
                        <span class="certified-drmtype"></span>
                    </div>
                    <div class="auth-guide">
                        <span class="auth-message">라이브 시청을 위해서는 이메일 인증이 필요합니다</span>
                        <a class="auth-button" href="#">인증하기</a>
                    </div>
                </div>
            </div>
            <div class="left-player">
                <video id='video'  class="video-js vjs-default-skin vjs-big-play-centered vjs-show-big-play-button-on-pause" controls autoplay="true" muted="muted" preload="none" data-setup='{"techorder" : ["flash", "html5"], "loop" : "true"}' loop>
            </div>
        </div>
        <div class="live-right">
            <div class="body-chat">
                <div>
                    채팅테스트
                </div>
            </div>
        </div>
    </section>
    <!-- End Live Section -->

    <!-- User certify -->
    <div class="popup">
        <form >
            <div class="popup_body email">
                <p>이메일을 입력해주세요</p>
                <input class="popup__input input_email" name="email" type="email">
                <button class="popup__button submit submit_email" type="button">인증번호 전송</button>
            </div>
            <div class="popup_body certify-number current">
                <p>이메일 확인 후 인증번호를 입력해주세요</p>
                <input class="popup__input input_certify_number" name="certify-number" type="text">
                <button class="popup__button submit submit_certify_number" type="button">인증확인</button>
            </div>
            <button class="popup__button cancel"type="button">취소</button>
        </form>
    </div>
    <!-- End User certify -->
    
    <!-- live.js 유저 인증 및 플레이어 재생 -->
    <script src="js/live.js"></script>


    <script src="https://jaehyeok.ml/chat"></script>
</body>