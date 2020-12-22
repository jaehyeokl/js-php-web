<?php
    
    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결
    
    // $postId = $_GET['id']; // 게시글 id

    // TODO: 작성자 불러올경우에는 JOIN 을 통하여 한번의 쿼리문으로 함께 불러올 수 있도록 하자
    // TODO: 수정일을 보여줄 수 있는 방법을 생각해보자
    // $getPostStatement = $connectDB->prepare("SELECT * FROM blog JOIN image ON blog.contentsImageId = image.imageId WHERE id = :id");
    // $getPostStatement->bindParam(':id', $id, PDO::PARAM_INT);
    
    // $id = 2;
    // $getPostStatement->execute();
    
    // $postRow = $getPostStatement->fetch();
    // // $writerId = $row['creater'];
    // // $title = $postRow['title'];
    // // $contentsText = $postRow['contentsText'];
    // // $createdAt = $postRow['createdAt'];

    // print_r($postRow);

    if (!null) {
        
    }
?>