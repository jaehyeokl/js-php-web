<?php
    
    if (!empty($_POST)) {

        include_once("../resources/config.php");
        $connectDB = connectDB(); // DB 연결

        

        // 댓글, 대댓글 공통 변수
        $postId = $_POST['postId'];
        $groupNum = $_POST['groupNum'];
        $nestedOrder = $_POST['nestedOrder'];
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        
        $currentAt = date('Y-m-d H:i:s'); // 수정 및 삭제시간

        if ($_POST['state'] == 0) {
            // 댓글 수정일때
            
            $modifyCommentStatement = $connectDB->prepare("UPDATE comments SET name = :name, comment = :comment, updatedAt = :updatedAt
                                    WHERE postId = :postId AND groupNum = :groupNum AND nestedOrder = :nestedOrder");
            
            $modifyCommentStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
            $modifyCommentStatement->bindParam(':groupNum', $groupNum, PDO::PARAM_INT);
            $modifyCommentStatement->bindParam(':nestedOrder', $nestedOrder, PDO::PARAM_INT);
            $modifyCommentStatement->bindParam(':name', $name);
            $modifyCommentStatement->bindParam(':comment', $comment);
            $modifyCommentStatement->bindParam(':updatedAt', $currentAt);
            $modifyCommentStatement->execute();

            $message = "수정";
        }
        
        if ($_POST['state'] == 1) {
            // 댓글 삭제일때

            $deleteCommentStatement = $connectDB->prepare("UPDATE comments SET deletedAt = :deletedAt
                                    WHERE postId = :postId AND groupNum = :groupNum AND nestedOrder = :nestedOrder");
            $deleteCommentStatement->bindParam(':postId', $postId, PDO::PARAM_INT);
            $deleteCommentStatement->bindParam(':groupNum', $groupNum, PDO::PARAM_INT);
            $deleteCommentStatement->bindParam(':nestedOrder', $nestedOrder, PDO::PARAM_INT);
            $deleteCommentStatement->bindParam(':deletedAt', $currentAt);
            $deleteCommentStatement->execute();

            $message = "삭제";
        }

        echo "<script>
                    alert('$message 되었습니다');
                    window.close();
                </script>";
        
    } else {
        // 정상적인 경로가 아닙니다
        echo "<script>
                alert('정상적인 경로가 아닙니다');
                location.replace('https://jaehyeok.ml');
            </script>";
    }

?>