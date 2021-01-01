<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus();

    echo $userSessionStatus[0];
    echo $userSessionStatus[1];
    echo $userSessionStatus[2];
?>