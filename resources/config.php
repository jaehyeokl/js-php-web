<!-- Database 'portfolio' 연결 -->
<?php
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


?>
