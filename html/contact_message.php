<?php
    // config.php 에 설정된 함수 sendContactEmail($nameFrom, $mailFrom, $message)
    // 을 통하여 나의 이메일 계정으로 메일을 전송한다
    include_once("../resources/config.php");

    $nameFrom = $_POST['name'];
    $mailFrom = $_POST['email'];
    $message = $_POST['message'];

    sendContactEmail($nameFrom, $mailFrom, $message);

    echo "
        <script>
            alert('전송 완료!');
            location.href = 'https://jaehyeok.ml/#contact';
        </script>
        ";

    // mail() 함수를 사용하기 위해서 sendmail 설치 해야한다
    // (sudo apt-get install sendmail)  
    // mail() 전송 완료로 뜨지만, 실제로 메일 수신하지않음
    // $to = "hyukzza@naver.com";
    // $subject = "PHP 메일 발송";
    // $contents = "PHP mail()함수를 이용한 메일 발송 테스트";
    // $headers = "From: test1111@naver.com\r\n";
    // $result = mail($to, $subject, $contents, $headers);

    // if($result) {
    //     echo "성공";
    //     print_r(error_get_last()['message']);
    //     // var_dump(error_get_last()['message']);

    // } else {
    //     echo "실패";
    //     print_r(error_get_last()['message']);
    //     var_dump(error_get_last()['message']);
    // }
?>