<?php 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <script src="/chat/node_modules/socket.io/client-dist/socket.io.js"></script> -->
    <!-- <script src="/chat/node_modules/socket.io/socket.io.js"></script> -->
    <!-- <script src="https://jaehyeok.ml/chat"></script> -->
</head>
<body>
    <!-- <a href="https://jaehyeok.ml/chat">dd</a> -->
    <!-- <script src="/chat/socket.io/socket.io.js"></script> -->
    <script src="/chat/node_modules/socket.io/client-dist/socket.io.js"></script>
    <script>

        // let socket = io.connect()
        let socket = io.connect("https://jaehyeok.ml:3000", { transports: ['websocket'] })

        // socket.on("someEvent", data => {
        //     console.log(`I can now do something with ${data}`);
        // });
        console.log("hello")

        // 클라이언트가 채팅방에 참가 // 

        // 클라이언트가 채팅에 참여할 이름을 서버에 전달한다
        // socket.emit("join_chat", "testName");

        // // 채팅방 참가(join_caht) 인원이 있을때마다 서버로부터 참가자 데이터를 전달받는다 
        // socket.on("join_chat", (name) => {
        //     let messageTag = document.createElement("li");
        //     let joinMessage = name + "님이 입장하였습니다";
        //     // css 적용 위한 class 부여
        //     messageTag.classList.add("join-message");
        //     // 채팅창에 메세지 반영
        //     document.querySelector(".chat__messages").append(messageTag);
        //     messageTag.append(joinMessage);
        // });


        // // 채팅 메세지 작성, 전달 //

        // // 메세지 전송버튼을 눌렀을때 서버로 메세지내용과 유저이름을 전달한다
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


        // // 다른 유저의 메세지를 채팅창에 반영 //

        // // 서버로부터 다른 클라이언트가 작성한 메세지를 전달받는다
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
</html>