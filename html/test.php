<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus();
    echo "<script>"."alret(".$signinSessionStatus[2].");</script>";


    echo $signinSessionStatus[0];
    echo $signinSessionStatus[1];
    echo $signinSessionStatus[2];
?>