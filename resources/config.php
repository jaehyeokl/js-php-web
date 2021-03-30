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

// 방문자 정보 저장
function logVisitor() {
    // 마지막 방문시간을 세션에 저장한다
    // 재방문 시간이 저장된 방문시간 3시간 이내일 경우에는 같은 방문자로 판단 (DB 저장 X)
    // 3시간 이후 방문 시 새로운 방문자 row 생성 및 저장
    
    $connectDB = connectDB(); // DB 연결
    session_start();

     // 변수 초기화
     $getUserAgent = get_browser(null, true);

     $userIp = $_SERVER['REMOTE_ADDR'];
     $visitedAt = date('Y-m-d H:i:s');
     $os = $getUserAgent['platform'];
     $browser = $getUserAgent['browser'];
     $country = geoip_country_code3_by_name($userIp); // 국가
     $referer = $_SERVER['HTTP_REFERER'];

     // visitLog 테이블에 새로운 방문자 기록 저장하는 statement
     $insertVisitLogStatement = $connectDB->prepare("INSERT INTO visitLog (visitedAt, ip, os, browser, country, referer) 
                                                     VALUES (:visitedAt, :ip, :os, :browser, :country, :referer)");
    $insertVisitLogStatement->bindParam(':visitedAt', $visitedAt);
    $insertVisitLogStatement->bindParam(':ip', $userIp);
    $insertVisitLogStatement->bindParam(':os', $os);
    $insertVisitLogStatement->bindParam(':browser', $browser);
    $insertVisitLogStatement->bindParam(':country', $country);
    $insertVisitLogStatement->bindParam(':referer', $referer);
    
    if(isset($_SESSION['latestVisitedAt'])) { 
        // 세션에 마지막 방문 시간이 저장되어 있을때
        // 마지막 방문으로부터 3시간이 지났을때 -> 새로운 방문자로 판단하여 DB 에 기록 저장
        // 3시간 이내일때 -> 기존 방문자로 판단

        // 시간 차 구하기
        $latestVisitedAt = $_SESSION['latestVisitedAt'];
        $visitTerm = (strtotime($visitedAt) - strtotime($latestVisitedAt)) / 3600;

        if ($visitTerm > 3) {
            // 3시간 이후 방문
            $insertVisitLogStatement->execute();
        }
    
    } else {
        // 마지막 방문 시간을 저장하는 세션이 없을때 (-> 첫 방문일때)
        // DB 에 방문 기록을 저장한다
        $insertVisitLogStatement->execute();
    }

    // 마지막 방문시간 업데이트
    $_SESSION['latestVisitedAt'] = $visitedAt;
    $connectDB = null;
}

// Contact 메일 보내기
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
function sendContactEmail($nameFrom, $mailFrom, $message) {
    // 메소드의 인자로 보내는사람의 이름, 이메일, 메세지내용을 받아
    // 나의 계정 hyukzza@gmail.com 으로 보낸다

    require '../resources/library/PHPMailer/src/Exception.php';
    require '../resources/library/PHPMailer/src/PHPMailer.php';
    require '../resources/library/PHPMailer/src/SMTP.php';
    
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch $mail->IsSMTP(); // telling the class to use SMTP
    $mail->IsSMTP(); // telling the class to use SMTP
    try {
        $mail->CharSet = "utf-8"; //한글이 안깨지게 CharSet 설정
        $mail->Encoding = "base64";
        $mail->Host = "smtp.naver.com"; // email 보낼때 사용할 서버를 지정
        // $mail->Host = "smtp.gmail.com"; // email 보낼때 사용할 서버를 지정
        $mail->SMTPAuth = true; // SMTP 인증을 사용함
        $mail->Port = 465; // email 보낼때 사용할 포트를 지정
        $mail->SMTPSecure = "ssl"; // SSL을 사용함
        $mail->Username = "hyukzza@naver.com"; // 송신할 이메일
        // $mail->Username = "hyukzza@gmail.com"; // 송신할 이메일
        $mail->Password = "wogur188@@"; // 패스워드
        // $mail->Password = "dprhfprh"; // 패스워드
        $mail->SetFrom('hyukzza@naver.com', $mailFrom); // 보내는 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
        $mail->AddAddress('hyukzza@naver.com'); // 받을 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
        $mail->Subject = '[Contact from portfolio]'; // 메일 제목
        $mail->Body = "[보내는 사람 : $nameFrom]\n\n".$message; // 메세지
        $mail->Send(); // 발송
        
        //echo "Message Sent OK //발송 확인\n";
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
}

function sendLiveCertifyEmail($emailTo, $message) {
    require '../resources/library/PHPMailer/src/Exception.php';
    require '../resources/library/PHPMailer/src/PHPMailer.php';
    require '../resources/library/PHPMailer/src/SMTP.php';
    
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch $mail->IsSMTP(); // telling the class to use SMTP
    $mail->IsSMTP(); // telling the class to use SMTP
    try {
        $mail->CharSet = "utf-8"; //한글이 안깨지게 CharSet 설정
        $mail->Encoding = "base64";
        $mail->Host = "smtp.naver.com"; // email 보낼때 사용할 서버를 지정
        // $mail->Host = "smtp.gmail.com"; // email 보낼때 사용할 서버를 지정
        $mail->SMTPAuth = true; // SMTP 인증을 사용함
        $mail->Port = 465; // email 보낼때 사용할 포트를 지정
        $mail->SMTPSecure = "ssl"; // SSL을 사용함
        $mail->Username = "hyukzza@naver.com"; // 송신할 이메일
        // $mail->Username = "hyukzza@gmail.com"; // 송신할 이메일
        $mail->Password = "wogur188@@"; // 패스워드
        // $mail->Password = "dprhfprh"; // 패스워드
        $mail->SetFrom('hyukzza@naver.com', "JaeHyeok"); // 보내는 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
        $mail->AddAddress($emailTo); // 받을 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
        $mail->Subject = '[Live Streaming Certify]'; // 메일 제목
        $mail->Body = "[Live Streaming 인증번호]"."\n\n".$message; // 메세지
        $mail->Send(); // 발송
        
        echo "Message Sent OK //발송 확인\n";
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
}
?>
