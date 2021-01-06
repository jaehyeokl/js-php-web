<?php
    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결

    if (!empty($_POST)) {
        // 댓글, 대댓글 DB 저장\
        $createCommentStatement = $connectDB->prepare("INSERT INTO comments (postId, nestedOrder, groupNum, name, password, comment, createdAt) 
                                    VALUES (:postId, :nestedOrder, :groupNum, :name, :password, :comment, :createdAt)");
        $createCommentStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $createCommentStatement->bindParam(':nestedOrder', $nestedOrder, PDO::PARAM_INT);
        $createCommentStatement->bindParam(':groupNum', $groupNum, PDO::PARAM_INT);
        $createCommentStatement->bindParam(':name', $name);
        $createCommentStatement->bindParam(':password', $password);
        $createCommentStatement->bindParam(':comment', $comment);
        $createCommentStatement->bindParam(':createdAt', $createdAt);

        // 데이터 입력 후 실행
        $postId = $_POST['postId'];
        $nestedOrder = $_POST['nested'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $comment = $_POST['comment'];
        $createdAt = date('Y-m-d H:i:s');

        // GroupNum 은 한 게시글에서 기본 댓글과 그 하위 대댓글을 포함한 그룹을 말한다
        // 고유한 숫자를 가져야 하기 때문에 총 댓글 수에서 + 1 을 하여 Auto Increase 되도록 한다
        $getGroupNumStatement = $connectDB->prepare("SELECT * FROM comments WHERE postId = :postId AND nestedOrder = 0");
        $getGroupNumStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $getGroupNumStatement->execute();
        $groupNum = $getGroupNumStatement->rowCount() + 1;

        $createCommentStatement->execute();
        
    } else {
        // 정상적인 경로가 아닙니다
        echo "<script>
                alert('정상적인 경로가 아닙니다');
                location.replace('https://jaehyeok.ml');
            </script>";
    }



    

    




    // AJAX 를 통해 전달받은 데이터
    // $data = $_POST['data'];
    // $postId = $data['postId'];


    // // JSON 으로 데이터 반환할때
    // header("Content-Type: application/json; charset=utf-8");
    // echo json_encode( $data, JSON_UNESCAPED_UNICODE );
?>