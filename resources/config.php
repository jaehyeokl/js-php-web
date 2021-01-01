<?php
// 웹사이트에 필요한 설정을 메소드로 구현 
// 호출하여 사용

// 사이트 IP
function getIP() {
    $ip = "52.79.61.49";
    return $ip;
}

// 로그인 여부 확인
function checkSigninStatus() {
    session_start();

    // SESSION 전역변수의 존재여부를 통해 로그인상태 체크
    // 로그인 상태일때 세션에 저장된 변수를 담은 배열을 반환한다
    // (로그인 상태가 아닐 시 false 반환)
    if (isset($_SESSION['userId'])) {
        $signinSessionArray = array($_SESSION['userId'], $_SESSION['email'], $_SESSION['manager']); 
        return $signinSessionArray;
    } else {
        return false;
    }
}

// Database 'portfolio' 연결
function connectDB() {
    $servername = "localhost";
    $port = "3306";
    $dbname = "portfolio";
    $user = "manager";
    $password = "8fuch#CJjcyW8zHgLISW27^J8D4NB4VI^fLMp4XtR0ZO7!4u&k";

    try {
        $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $user, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "connect!";
    } catch (PDOException $ex) {
        echo "failed! : " . $ex->getMessage() . "<br>";
    }

    return $conn;
}   
?>
