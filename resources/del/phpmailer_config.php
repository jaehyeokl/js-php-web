<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    
    require '/var/www/portfolio/resources/library/PHPMailer/src/Exception.php';
    require '/var/www/portfolio/resources/library/PHPMailer/src/PHPMailer.php';
    require '/var/www/portfolio/resources/library/PHPMailer/src/SMTP.php';
    
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch $mail->IsSMTP(); // telling the class to use SMTP
    $mail->IsSMTP(); // telling the class to use SMTP
    try {
        $mail->CharSet = "utf-8"; //한글이 안깨지게 CharSet 설정
        $mail->Encoding = "base64";
        $mail->Host = "smtp.naver.com"; // email 보낼때 사용할 서버를 지정
        $mail->SMTPAuth = true; // SMTP 인증을 사용함
        $mail->Port = 465; // email 보낼때 사용할 포트를 지정
        $mail->SMTPSecure = "ssl"; // SSL을 사용함
        $mail->Username = "hyukzza@naver.com"; // 송신할 이메일
        $mail->Password = "wogur188@@"; // 패스워드
        $mail->SetFrom($mailFrom, $nameFrom); // 보내는 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
        $mail->AddAddress('hyukzza@gmail.com'); // 받을 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
        $mail->Subject = '[Contact from portfolio]'; // 메일 제목
        $mail->Body = $message; // 메세지
        // $mail->Send(); // 발송
        
        echo "Message Sent OK //발송 확인\n";
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
?>