<?php
    // 세션 로그아웃
    session_start();
    session_unset();
    session_destroy();

    header("Location: https://jaehyeok.ml");
    die();
?>