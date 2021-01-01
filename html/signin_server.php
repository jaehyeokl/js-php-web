<?php
    // 로그인 처리

    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결

    // 유저 입력 이메일/ 비밀번호
    $inputEmail = $_POST['email'];
    $inputPassword = $_POST['password'];

    try {
        $checkUserStatement = $connectDB->prepare("SELECT * FROM user WHERE email = :email");
        $checkUserStatement->bindParam(':email', $inputEmail);
        $checkUserStatement->execute();
        
        if ($checkUserStatement->rowCount() == true) {
            // 가입된 계정일때
            $userDataRow = $checkUserStatement->fetch();
            $password = $userDataRow['password'];

            // 유저가 입력한 비밀번호가 DB에 저장된 비밀번호와 일치하는지 확인
            // 일치할 경우 세션에 해당 이메일을 저장하여 로그인 상태를 부여한다
            if ($password === $inputPassword) {
                session_start();
                $_SESSION['userId'] = $userDataRow['id'];
                $_SESSION['email'] = $userDataRow['email'];
                $_SESSION['manager'] = $userDataRow['manager'];
                
                header("Location: https://jaehyeok.ml");
                die();

            } else {
                echo "
                    <script>
                        alert('비밀번호가 일치하지 않습니다');
                        history.back();
                    </script>
                ";
            }

        } else {
            // 가입된 계정이 아닐때
            echo "
                <script>
                    alert('존재하지 않는 계정입니다');
                    history.back();
                </script>
                ";
        }

    } catch (PDOException $ex) {
        echo "failed! : ".$ex->getMessage()."<br>";
    }
    $connectDB = null;
?>