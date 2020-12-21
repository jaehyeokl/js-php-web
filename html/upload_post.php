<!-- 블로그 게시글을 생성하거나 수정, 삭제할때 실행 
    데이터베이스 blog 테이블의 게시글에 대한 데이터를 생성,수정 한다
    (삭제일 경우 DB에서 삭제가 아닌 deletedAt 컬럼에 삭제시간을 추가하여 삭제된것처럼 처리한다) -->

<?php
    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결
    $ip = getIP(); // 사이트의 ip 가져오기

    // 게시글 생성, 수정, 삭제 상태를 나타내는 변수 초기화
    $CREATE_POST = 1;
    $MODIFY_POST = 2;
    $DELETE_POST = 3;

    // 게시글 생성/수정/삭제 상태 체크
    $modeState = getModeState();    
    
    try {
        switch ($modeState) {
            case $CREATE_POST:
                // 새 게시글 작성
                $createPostStatement = $connectDB->prepare("INSERT INTO blog (writerId, title, contentsText, createdAt) 
                VALUES (:writerId, :title, :contentsText, :createdAt)");    
                // $createPostStatement->bindParam(':creater', $_SESSION['email']); // 세션에 저장된 email 을 작성자로 추가
                $createPostStatement->bindParam(':writerId', $writerId, PDO::PARAM_INT);
                $createPostStatement->bindParam(':title', $title);
                $createPostStatement->bindParam(':contentsText', $contentsText);
                $createPostStatement->bindParam(':createdAt', $createdAt);

                // DB 쿼리문에 bind 할 데이터를 초기화한다
                $writerId = 1;
                // TODO: 회원가입 구현 이후, 로그인한 계정(관리자계정)의 user 테이블 id 값을 사용하도록
                $title = $_POST['title'];
                $contentsText = $_POST['contents_text'];
                // MYSQL 의 NOW() 처럼 현재시간을 구하는 함수
                // 게시글 생성시간을 한국시간으로 생성하기 위한 설정
                // date_default_timezone_set("Asia/Seoul");
                // 기본적으로 한국시간 가져오기 위해 php.ini 파일의 date.timezone 을 Asia/Seoul로 설정하였음
                $createdAt = date('Y-m-d H:i:s');
                $createPostStatement->execute();
                break;

            case $MODIFY_POST:
                // 기존 게시글 수정
                $modifyPostStatement = $connectDB->prepare("UPDATE blog SET title = :title, contentsText = :contentsText, 
                updatedAt = :updatedAt WHERE id = :id");
                $modifyPostStatement->bindParam(':title', $title);
                $modifyPostStatement->bindParam(':contentsText', $contentsText);
                $modifyPostStatement->bindParam(':updatedAt', $updatedAt);
                $modifyPostStatement->bindParam(':id', $postId, PDO::PARAM_INT);
                // 데이터 입력 후 실행
                $title = $_POST['title'];
                $contentsText = $_POST['contents_text'];
                $updatedAt = date('Y-m-d H:i:s');
                $postId = $_GET['id'];
                $modifyPostStatement->execute();
                break;

            case $DELETE_POST:
                // 기존 게시글 삭제
                // 데이터베이스 blog 테이블의 deletedAt 컬럼에 삭제 시간을 입력함으로써 게시글 삭제처리
                $deletePostStatement = $connectDB->prepare("UPDATE blog SET deletedAt = :deletedAt WHERE id = :id");
                $deletePostStatement->bindParam(':deletedAt', $deletedAt);
                $deletePostStatement->bindParam(':id', $postId, PDO::PARAM_INT);
                
                $deletedAt = date('Y-m-d H:i:s');
                $postId = $_GET['id'];
                $deletePostStatement->execute();
                break;
        }
    } catch (PDOException $ex) {
        echo "failed! : ".$ex->getMessage()."<br>";
    }
    $connectDB = null;

    // 게시글 추가, 수정 삭제 DB 작업 완료 이후 전환될 페이지 설정
    switch ($modeState) {
        case $CREATE_POST:
            // 새 게시글 작성 : 게시글 목록보기
            header("Location: http://".$ip."/blog.php");
            die();
            break;

        case $MODIFY_POST:
            // 게시글 수정 : 수정한 게시글 보기
            header("Location: http://".$ip."/view_post.php?id=".$postId);
            die();
            break;

        case $DELETE_POST:
            // 게시글 작성 : 게시글 목록으로 돌아가기
            header("Location: http://".$ip."/blog.php");
            die();
            break;
    }

    // 게시글 생성, 수정, 삭제 상태 확인하여 반환하는 메소드
    function getModeState() {
        $CREATE_POST = 1;
        $MODIFY_POST = 2;
        $DELETE_POST = 3;

        $modeState = $CREATE_POST;
        if (isset($_GET['mode'])) {
            $getMode = $_GET['mode'];
            switch ($getMode) {
                case "modify":
                    $modeState = $MODIFY_POST;
                    break;
                case "delete":
                    $modeState = $DELETE_POST;
                    break;
            }
        } else {
            $modeState = $CREATE_POST;
        }
        return $modeState;
    }
?>