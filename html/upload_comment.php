<?php
    
    if (!empty($_POST)) {

        include_once("../resources/config.php");
        $connectDB = connectDB(); // DB 연결

        // 데이터베이스 comments 테이블에 댓글 데이터 저장
        $createCommentStatement = $connectDB->prepare("INSERT INTO comments (postId, nestedOrder, groupNum, name, password, comment, createdAt) 
                VALUES (:postId, :nestedOrder, :groupNum, :name, :password, :comment, :createdAt)");
        $createCommentStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
        $createCommentStatement->bindParam(':nestedOrder', $nestedOrder, PDO::PARAM_INT);
        $createCommentStatement->bindParam(':groupNum', $groupNum, PDO::PARAM_INT);
        $createCommentStatement->bindParam(':name', $name);
        $createCommentStatement->bindParam(':password', $password);
        $createCommentStatement->bindParam(':comment', $comment);
        $createCommentStatement->bindParam(':createdAt', $createdAt);

        // 댓글, 대댓글 공통 변수
        $postId = $_POST['postId'];
        $nestedOrder = $_POST['nested'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $comment = $_POST['comment'];
        $createdAt = date('Y-m-d H:i:s');

        if ($_POST['groupNum'] == null) {
            // 일반 댓글일때

            // 일반 댓글일 경우에는 테이블의 nestedOrder 컬럼의 값이 0
            $nestedOrder = 0;
            
            // GroupNum 은 한 게시글에서 기본 댓글과 그 하위 대댓글을 포함한 그룹을 말한다
            // 고유한 숫자를 가져야 하기 때문에 총 댓글 수에서 + 1 을 하여 Auto Increase 되도록 한다
            $getGroupNumStatement = $connectDB->prepare("SELECT * FROM comments WHERE postId = :postId AND nestedOrder = 0");
            $getGroupNumStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
            $getGroupNumStatement->execute();
            $groupNum = $getGroupNumStatement->rowCount() + 1;

            $createCommentStatement->execute();

            echo "<script>
                    alert('댓글을 작성하였습니다');
                    history.back();
                </script>";
        } else {
            // 대댓글일때
            // 대댓글일때는 $_POST['groupNum'] 으로 대댓글이 포함될 groupNum 을 전달받는다

            // 대댓글을 저장하기 위해 필요한 nestedOrder 구하기
            // nestedOrder 란 같은 게시글의 댓글 그룹(groupNum) 에서 해당 대댓글이 몇번째 대댓글인지를 나타내는 숫자이다
            // 고유한 숫자를 가지기 위해 같은 그룹의 총 대댓글 수에서 +1 하여 Auto Increase 되도록 한다
            $getNestedOrderStatement = $connectDB->prepare("SELECT * FROM comments WHERE postId = :postId AND groupNum = :groupNum AND nestedOrder != 0");
            // 쿼리문에서 일반 댓글 (nestedOrder = 0) 일때는 제외한다
            $getNestedOrderStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
            $getNestedOrderStatement->bindParam(':groupNum', $groupNum, PDO::PARAM_INT);
            $groupNum = $_POST['groupNum'];
            $getNestedOrderStatement->execute();
            
            $nestedOrder = $getNestedOrderStatement->rowCount() + 1;

            // groupNum 와 nestedOrder 변수 지정 했으니 DB 저장
            $createCommentStatement->execute();

            echo "<script>
                    alert('댓글을 작성하였습니다');
                    opener.location.reload();
                    window.close();
                </script>";
        }
        
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