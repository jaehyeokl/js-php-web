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
    <meta name="description" content="Live">
    <meta property="og:title" content="JaeHyeok's Portfolio" />
    <meta property="og:description" content="개발자 이재혁 포트폴리오 사이트" />
    <meta name="keywords" content="개발자 포트폴리오" />
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
            <button class="join-chat">채팅 참가</button>
            <div class="body-chat"></div>
            <div class="input-chat">
                <input class="input-message" type="text">
                <input class="send-message" type="submit">
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

    <!-- 실시간 채팅 -->
    <script src="/chat/node_modules/socket.io/client-dist/socket.io.js"></script>
    <script src="/chat/chat_client.js"></script>
    <script>
        // let socket = io.connect("https://jaehyeok.ml:3000", { transports: ['websocket'] })
        // let name = "testName"

        // // 클라이언트가 채팅방에 참가 // 

        // // 클라이언트가 채팅에 참여할 이름을 서버에 전달한다
        // socket.emit("join_chat", name);

        // // 채팅방 참가(join_caht) 인원이 있을때마다 서버로부터 참가자 데이터를 전달받는다 
        // socket.on("join_chat", (name) => {
        //     let messageTag = document.createElement("div");
        //     let joinMessage = name + "님이 입장하였습니다";
        //     // css 적용 위한 class 부여
        //     messageTag.classList.add("join-message");
        //     // 채팅창에 메세지 반영
        //     document.querySelector(".body-chat").append(messageTag);
        //     messageTag.append(joinMessage);
        // });


        // 채팅 메세지 작성, 전달 //

        // 메세지 전송버튼을 눌렀을때 서버로 메세지내용과 유저이름을 전달한다
        // let sendMessage = document.querySelector(".input__send");
        // sendMessage.addEventListener("click", () => {
        //     // 입력한 메세지 내용
        //     let message = document.querySelector(".input__message").value;
        //     // 내용을 입력하지 않고 전송버튼을 눌렀을때는 해당 이벤트를 취소
        //     if (message.length > 0) {
        //         // 유저와 메세지내용이 담긴 객체를 서버로 전달한다
        //         let sendMessageData = {
        //         name : name,
        //         message : message
        //         }; 
        //         socket.emit("send_message", sendMessageData);

        //         // 현 클라이언트에서 전달한 메세지는 서버로부터 전달받지 않고 바로 채팅창에 반영한다
        //         // 다른 메세지들과 구분할 수 있도록 하기 위해서 ex([나] : message)
        //         let messageTag = document.createElement("li");
        //         let chatMessage = "[나] : " + message;
        //         // css 적용 위한 class 부여
        //         messageTag.classList.add("my-message");
        //         // 채팅창에 메세지 반영
        //         document.querySelector(".chat__messages").append(messageTag);
        //         messageTag.append(chatMessage);
        //         // 채팅창의 스크롤을 가장 아래로 이동(최근 메세지 보기)
        //         let chatScroll = document.querySelector(".chat__scroll");
        //         chatScroll.scrollTop = chatScroll.scrollHeight;
        //     } else {
        //         event.preventDefault();
        //         event.stopPropagation();
        //     }
        // });
        // // 입력창에서 엔터를 눌렀을때도 전송버튼을 클릭하도록 설정
        // let inputMessage = document.querySelector(".input__message");
        // inputMessage.addEventListener("keypress", () => {
        //     let message = document.querySelector(".input__message").value;

        //     if (window.event.keyCode == 13) {
        //         if (message.length > 0) {
        //             sendMessage.click();
        //         } else {
        //             event.preventDefault();
        //             event.stopPropagation();
        //         }
        //     }
        // });


        // 다른 유저의 메세지를 채팅창에 반영 //

        // 서버로부터 다른 클라이언트가 작성한 메세지를 전달받는다
        // socket.on("send_message", (data) => {
        //     // console.log(data.name);
        //     // console.log(data.message);

        //     let messageTag = document.createElement("li");
        //     let chatMessage = data.name + " : " + data.message;
        //     // css 적용 위한 class 부여
        //     messageTag.classList.add("other-message");
        //     // 채팅창에 메세지 반영
        //     document.querySelector(".chat__messages").append(messageTag);
        //     messageTag.append(chatMessage);
        //     // 채팅창의 스크롤을 가장 아래로 이동(최근 메세지 보기)
        //     let chatScroll = document.querySelector(".chat__scroll");
        //     chatScroll.scrollTop = chatScroll.scrollHeight;
        // });


        // // 연결 끊었을때 (채팅방 나가기)
        // socket.on('disconnect', (name) => {
        //     console.log("disconnected from server");
        // });

    </script>
</body>