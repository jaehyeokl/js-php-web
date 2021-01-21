<?php 
    // 라이브 스트리밍을 보기위해 유저가 입력한 인증번호와, authId 를 전달받아
    // DB에 저장된 인증번호와 일치하는지 확인한다

    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결
    
    // 전달받은 데이터
    $getData = json_decode(file_get_contents('php://input'), true);
    $userInputCertifyNumber = $getData['certifyNumber'];
    $authId = $getData['authId'];
    // $authId = 1;

    // DB 에 저장된 인증번호 풀러오기
    $checkCertifyNumber = $connectDB->prepare("SELECT * FROM liveAuth WHERE id = :authId");    
    // $checkUserStatement = $connectDB->prepare("SELECT * FROM user WHERE email = :email");
    $checkCertifyNumber->bindParam(':authId', $authId, PDO::PARAM_INT);
    $checkCertifyNumber->execute();
    $row = $checkCertifyNumber->fetch();
    $certifyNumber = $row['certifyNumber'];

    // // 일치 여부 확인
    $result = false;
    
    if ($userInputCertifyNumber == $certifyNumber) {
        $result = true;
    }
    
    // 결과 return
    echo json_encode(['result' => $result], JSON_THROW_ON_ERROR, 512);
?>