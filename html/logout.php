<?php
    // 세션 로그아웃
    session_start();
    unset($_SESSION['userId']);
    // 세션에 방문자 로그정보도 저장하기 때문에
    // 세션 삭제 또는 파일삭제를 하지는 않고 로그인 정보가 저장된 세션만 초기화시킨다
    // session_unset();
    // session_destroy();

    header("Location: https://jaehyeok.ml");
    die();
?>