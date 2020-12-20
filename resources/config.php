<!-- 웹사이트에 필요한 설정을 메소드로 구현 -->
<!-- 호출하여 사용 -->

<?php
// 사이트 IP
function getIP() {
    $ip = "52.79.61.49";
    return $ip;
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
