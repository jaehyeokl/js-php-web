<?php
    // 인증번호를 생성한 후 이메일과 함께 인증번호 DB에 저장한다
    // 이후 해당 이메일로 인증번호를 전송한다
    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결
    
    $getData = json_decode(file_get_contents('php://input'), true);
    $emailTo = $getData['email'];
    // 임의의 6개 숫자 인증번호 생성
    $certifyNumber = sprintf('%06d',rand(100000,999999));

    sendLiveCertifyEmail($emailTo, $certifyNumber);
    
    // DB 저장
    $createdAt = date('Y-m-d H:i:s');

    $saveCertifyUser = $connectDB->prepare("INSERT INTO liveAuth (email, certifyNumber, createdAt) 
            VALUES (:email, :certifyNumber, :createdAt)");    
    $saveCertifyUser->bindParam(':email', $emailTo);
    $saveCertifyUser->bindParam(':certifyNumber', $certifyNumber);
    $saveCertifyUser->bindParam(':createdAt', $createdAt);
    $saveCertifyUser->execute();

    // DB에 저장된 게시글의 id
    $newAuthId = $connectDB->lastInsertId();
    
    // 발송 완료메세지 리턴
    // FIXME: sendLiveCertifyEmail 메소드에서 echo 로 리턴되는 메세지 때문에
    // 리턴되는 발송메세지를 JSON 으로 인식하지 못하는 오륙 생긴다
    // 이에 live.js 에서 json 이 아닌 text 로 처리하여 오류를 해결하였음
    echo json_encode([ 'authId' => $newAuthId], JSON_THROW_ON_ERROR, 512);
?>