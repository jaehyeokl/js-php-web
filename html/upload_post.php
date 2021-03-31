<!-- 블로그 게시글을 생성하거나 수정, 삭제할때 실행 
    데이터베이스 blog 테이블의 게시글에 대한 데이터를 생성,수정 한다
    (삭제일 경우 DB에서 삭제가 아닌 deletedAt 컬럼에 삭제시간을 추가하여 삭제된것처럼 처리한다) -->

<?php
    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결
    logVisitor(); // 방문 로그 체크 및 저장
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
                

                // 게시글 이미지 처리
                // 1. 임시폴더에 저장된 게시글의 이미지 파일을 업로드된 게시글의 이미지 저장을 위한 폴더로 이동
                // 2. 게시글에서 기록된 이미지의 임시경로를 변경된 경로로 수정 (img 태그의 src 경로를 수정)
                // 3. 게시글에 업로드된 이미지 중 첫번째 이미지를 썸네일 이미지로 만들기 (resize)
                
                // 게시글에 작성된 모든 이미지를 추출한다 (img 태그 정규표현식을 이용)
                preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $contentsText, $matches);
                $imgTagList = $matches[0]; // 게시글 이미지태그 전체 리스트(img 태그 포함)
                $imgSrcList = $matches[1]; // 게시글 이미지태그의 src(폴더 경로) 리스트
                                    
                // 게시글에 작성된 이미지가 있을때
                if (!empty($imgTagList)) {
                    
                    // 1
                    $uploadImgSrcList = array();

                    foreach($imgSrcList as $src) {
                        $srcFileName = explode("/", $src)[2]; // 이미지 파일명
                        $tmpImgSrc = $src; // 이미지의 임시폴더 경로
                        $uploadImgSrc = "img/post/".$srcFileName; // 파일명 그대로 이동할 경로
                        
                        // 임시폴더에서 게시글 이미지를 저장하는 폴더(img/post) 이미지 이동
                        if(file_exists($tmpImgSrc)) {
                            rename($tmpImgSrc, $uploadImgSrc);
                        }
                        
                        // uploadImgSrcList 는 기존 게시글에 저장된 이미지 경로를 replace 하기 위해 사용
                        array_push($uploadImgSrcList, $uploadImgSrc);
                    }                
                    
                    // 2
                    $contentsText = str_replace("editor_tmp", "post", $contentsText);
                    // TODO: 게시글에 경로 이름이 들어가면 오류가 생기게된다
                    // $contentsText = preg_replace($imgSrcList, $uploadImgSrcList, $contentsText);
                    // print_r($imgSrcList);
                    // print_r($uploadImgSrcList);
                    // var_dump($contentsText);
                    
                    // 3
                    $firstImgSrc = $uploadImgSrcList[0];
                    $thumbnailSrc = str_replace("post", "thumbnail", $firstImgSrc);
                    $thumbnailWidth = 300;
                    $thumbnailHeight = 240;
                    getImageThumbnail($firstImgSrc, $thumbnailSrc, $thumbnailWidth, $thumbnailHeight);
                } 

                // 게시글 동영상 처리
                // 1. 임시폴더에 저장된 게시글의 동영상 파일을 업로드된 게시글의 동영상 저장을 위한 폴더로 이동
                // 2. 게시글에서 기록된 동영상의 임시경로를 변경된 경로로 수정 (video 태그의 src 경로를 수정)
                // 3. 게시글에 업로드된 이미지 중 첫번째 이미지를 썸네일 이미지로 만들기 (resize)
                
                // 게시글에 작성된 모든 이미지를 추출한다 (img 태그 정규표현식을 이용)
                preg_match_all("/<video[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $contentsText, $matches);
                $videoTagList = $matches[0]; // 게시글 비디오태그 전체 리스트(video 태그 포함)
                $videoSrcList = $matches[1]; // 게시글 비디오태그의 src(폴더 경로) 리스트
                                    
                // 게시글에 작성된 동영상이 있을때
                if (!empty($videoTagList)) {
                    
                    // 1
                    $uploadvideoSrcList = array();

                    foreach($videoSrcList as $src) {
                        $srcFileName = explode("/", $src)[2]; // 비디오 파일명
                        $tmpVideoSrc = $src; // 동영상의 임시폴더 경로
                        $uploadVideoSrc = "video/post/".$srcFileName; // 파일명 그대로 이동할 경로
                        
                        // 임시폴더에서 게시글 동영상을 저장하는 폴더(video/post) 동영상 이동
                        if(file_exists($tmpVideoSrc)) {
                            rename($tmpVideoSrc, $uploadVideoSrc);
                        }
                        
                        // uploadVideoSrcList 는 기존 게시글에 저장된 동영상 경로를 replace 하기 위해 사용
                        array_push($uploadVideoSrcList, $uploadVideoSrc);
                    }                
                    
                    // 2
                    $contentsText = str_replace("editor_tmp", "post", $contentsText);
                    // TODO: 게시글에 경로 이름이 들어가면 오류가 생기게된다
                    
                    // 3
                    // TODO: 비디오 썸네일 생성 보류 : FFMpeg 객체 생성 실패
                    // $firstVideoSrc = $uploadVideoSrcList[0];
                    // $videoThumbnailSrc = str_replace("post", "thumbnail", $firstVideoSrc);
                    // $thumbnailWidth = 240;
                    // $thumbnailHeight = 240;
                    // getVideoThumbnail($firstVideoSrc, $videoThumbnailSrc, $thumbnailWidth, $thumbnailHeight);
                    // getVideoThumbnail($firstVideoSrc, $videoThumbnailSrc);
                }

                // DB 저장
                $createPostStatement = $connectDB->prepare("INSERT INTO blog (writerId, title, contentsText, createdAt, thumbnail) 
                VALUES (:writerId, :title, :contentsText, :createdAt, :thumbnail)");    
                // $createPostStatement->bindParam(':creater', $_SESSION['email']); // 세션에 저장된 email 을 작성자로 추가
                $createPostStatement->bindParam(':writerId', $writerId, PDO::PARAM_INT);
                $createPostStatement->bindParam(':title', $title);
                $createPostStatement->bindParam(':contentsText', $contentsText);
                $createPostStatement->bindParam(':createdAt', $createdAt);
                // 썸네일 경로( 게시글 이미지가 없을때 null)
                $createPostStatement->bindParam(':thumbnail', $thumbnailSrc);
                $createPostStatement->execute();
                
                // DB에 저장된 게시글의 id
                $newPostId = $connectDB->lastInsertId();

                // // 이미지 업로드(파일)여부 확인
                // // 이미지를 데이터베이스 image 테이블에 저장
                // if (!isset($_FILES['contents_file'])) {
                //     // 파일 전달 X
                //  } else {
                //     try {
                //         // 이미지 DB 저장 후, id 를 return 받음
                //         $imageId = imageUpload();
                //         // 생성된 게시글 데이터에 이미지정보 업데이트 (image 테이블에 저장된 imageId)
                //         $addImageStatement = $connectDB->prepare("UPDATE blog SET contentsImageId = :contentsImageId WHERE id = :id");
                //         $addImageStatement->bindParam(':contentsImageId', $imageId, PDO::PARAM_INT);
                //         $addImageStatement->bindParam(':id', $newPostId, PDO::PARAM_INT);
                //         $addImageStatement->execute();
                //     } catch(Exception $e) {
                //         echo '<h4>'.$e->getMessage().'</h4>';
                //     }
                //  }
                break;

            case $MODIFY_POST:
                
                // 아래 preg_match_all 에서 게시글의 이미지를 추출할 수 있도록 먼저 초기화한다
                $contentsText = $_POST['contents_text'];
                
                // 게시글에 작성된 모든 이미지를 추출한다 (img 태그 정규표현식을 이용)
                preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $contentsText, $matches);
                $imgTagList = $matches[0]; // 게시글 이미지태그 전체 리스트(img 태그 포함)
                $imgSrcList = $matches[1]; // 게시글 이미지태그의 src(폴더 경로) 리스트

                var_dump($imgTagList);
                echo "<br>";
                var_dump($imgSrcList);
                                    
                // 게시글에 작성된 이미지가 있을때
                if (!empty($imgTagList)) {
                    
                    // 1
                    $uploadImgSrcList = array();

                    foreach($imgSrcList as $src) {
                        $srcFileName = explode("/", $src)[2]; // 이미지 파일명
                        $tmpImgSrc = $src; // 이미지의 임시폴더 경로
                        $uploadImgSrc = "img/post/".$srcFileName; // 파일명 그대로 이동할 경로
                        
                        // 임시폴더에서 게시글 이미지를 저장하는 폴더(img/post) 이미지 이동
                        if(file_exists($tmpImgSrc)) {
                            rename($tmpImgSrc, $uploadImgSrc);
                        }
                        
                        // uploadImgSrcList 는 기존 게시글에 저장된 이미지 경로를 replace 하기 위해 사용
                        array_push($uploadImgSrcList, $uploadImgSrc);
                    }                
                    
                    // 2
                    $contentsText = str_replace("editor_tmp", "post", $contentsText);
                    // TODO: 게시글에 경로 이름이 들어가면 오류가 생기게된다
                    // $contentsText = preg_replace($imgSrcList, $uploadImgSrcList, $contentsText);
                    // print_r($imgSrcList);
                    // print_r($uploadImgSrcList);
                    // var_dump($contentsText);
                    
                    // 3
                    $firstImgSrc = $uploadImgSrcList[0];
                    $thumbnailSrc = str_replace("post", "thumbnail", $firstImgSrc);
                    $thumbnailWidth = 300;
                    $thumbnailHeight = 240;
                    getImageThumbnail($firstImgSrc, $thumbnailSrc, $thumbnailWidth, $thumbnailHeight);
                } 

                // 게시글 동영상 처리
                // 1. 임시폴더에 저장된 게시글의 동영상 파일을 업로드된 게시글의 동영상 저장을 위한 폴더로 이동
                // 2. 게시글에서 기록된 동영상의 임시경로를 변경된 경로로 수정 (video 태그의 src 경로를 수정)
                // 3. 게시글에 업로드된 이미지 중 첫번째 이미지를 썸네일 이미지로 만들기 (resize)
                
                // 게시글에 작성된 모든 이미지를 추출한다 (img 태그 정규표현식을 이용)
                preg_match_all("/<video[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $contentsText, $matches);
                $videoTagList = $matches[0]; // 게시글 비디오태그 전체 리스트(video 태그 포함)
                $videoSrcList = $matches[1]; // 게시글 비디오태그의 src(폴더 경로) 리스트
                                    
                // 게시글에 작성된 동영상이 있을때
                if (!empty($videoTagList)) {
                    
                    // 1
                    $uploadvideoSrcList = array();

                    foreach($videoSrcList as $src) {
                        $srcFileName = explode("/", $src)[2]; // 비디오 파일명
                        $tmpVideoSrc = $src; // 동영상의 임시폴더 경로
                        $uploadVideoSrc = "video/post/".$srcFileName; // 파일명 그대로 이동할 경로
                        
                        // 임시폴더에서 게시글 동영상을 저장하는 폴더(video/post) 동영상 이동
                        if(file_exists($tmpVideoSrc)) {
                            rename($tmpVideoSrc, $uploadVideoSrc);
                        }
                        
                        // uploadVideoSrcList 는 기존 게시글에 저장된 동영상 경로를 replace 하기 위해 사용
                        array_push($uploadVideoSrcList, $uploadVideoSrc);
                    }                
                    
                    // 2
                    $contentsText = str_replace("editor_tmp", "post", $contentsText);
                    // TODO: 게시글에 경로 이름이 들어가면 오류가 생기게된다
                    
                    // 3
                    // TODO: 비디오 썸네일 생성 보류 : FFMpeg 객체 생성 실패
                    // $firstVideoSrc = $uploadVideoSrcList[0];
                    // $videoThumbnailSrc = str_replace("post", "thumbnail", $firstVideoSrc);
                    // $thumbnailWidth = 240;
                    // $thumbnailHeight = 240;
                    // getVideoThumbnail($firstVideoSrc, $videoThumbnailSrc, $thumbnailWidth, $thumbnailHeight);
                    // getVideoThumbnail($firstVideoSrc, $videoThumbnailSrc);
                }
                // 기존 게시글 수정
                $modifyPostStatement = $connectDB->prepare("UPDATE blog SET title = :title, contentsText = :contentsText, 
                updatedAt = :updatedAt, thumbnail = :thumbnail WHERE id = :id");
                $modifyPostStatement->bindParam(':title', $title);
                $modifyPostStatement->bindParam(':contentsText', $contentsText);
                $modifyPostStatement->bindParam(':updatedAt', $updatedAt);
                $modifyPostStatement->bindParam(':thumbnail', $thumbnailSrc);
                $modifyPostStatement->bindParam(':id', $postId, PDO::PARAM_INT);
                // 데이터 입력 후 실행
                $title = $_POST['title'];
                // $contentsText = $_POST['contents_text'];
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
            header("Location: https://jaehyeok.ml/view_post.php?id=".$newPostId);
            die();
            break;

        case $MODIFY_POST:
            // 게시글 수정 : 수정한 게시글 보기
            header("Location: https://jaehyeok.ml/view_post.php?id=".$postId);
            die();
            break;

        case $DELETE_POST:
            // 게시글 작성 : 게시글 목록으로 돌아가기
            header("Location: https://jaehyeok.ml/blog.php");
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

    // 이미지 썸네일 생성
    // 이미지를 resize 하여 newfile 경로로 저장한다
    function getImageThumbnail($file, $newfile, $w, $h) {
        // 원본 이미지의 가로, 세로 사이즈
        list($width, $height) = getimagesize($file);

        // 원본 이미지를 불러온다
        // 여러 이미지 확장자(jpg, png, gif)를 처리할 수 있도록 분기한다
        if(strpos(strtolower($file), ".jpg")) {
            $originalSrc = imagecreatefromjpeg($file);
        } else if (strpos(strtolower($file), ".png")) {
            $originalSrc = imagecreatefrompng($file);
        } else if(strpos(strtolower($file), ".gif")) {
            $originalSrc = imagecreatefromgif($file);
        }

        // 썸네일 사이즈의 이미지 틀을 생성한다
        $tmpCreation = imagecreatetruecolor($w, $h);
        // 썸네일 이미지 틀에 맞게 원본이미지를 resize 한다
        imagecopyresampled($tmpCreation, $originalSrc, 0, 0, 0, 0, $w, $h, $width, $height);

        // 썸네일을 thumbnail 디렉토리에 저장한다
        if(strpos(strtolower($newfile), ".jpg")) {
            imagejpeg($tmpCreation, $newfile);
        } else if(strpos(strtolower($newfile), ".png")) {
            imagepng($tmpCreation, $newfile);
        } else if(strpos(strtolower($newfile), ".gif")) {
            imagegif($tmpCreation, $newfile);
        }
    }

    // 동영상 썸네일 생성
    // php-FFmpeg 라이브러리를 사용하여 썸네일 생성
    function getVideoThumbnail($video, $newThumbnail) {
        require_once("../resources/library/vendor/autoload.php");
        // $ffmpeg = FFMpeg\FFMpeg::create();
        
        var_dump(FFMpeg\FFMpeg::create());
        
        
        // $video = $ffmpeg->open($video);
        // $video
        //     ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(1))
        //     ->addFilter(new CustomFrameFilter('scale=320x160')) //resize output frame image
        //     ->save($newThumbnail);
        // echo "??";

            // $ffmpeg = FFMpeg::create();
            // $ffmpeg->open('video path')
            //        ->frame(TimeCode::fromSeconds(1))
            //        ->addFilter(new CustomFrameFilter('scale=320x160')) //resize output frame image
            //        ->save('save path');    

        
    }

    // // 업로드된 이미지 파일을 DB에 저장
    // function imageUpload() {
    //     // 파일이 정상적으로 서버에 업로드 되었을때
    //     if (is_uploaded_file($_FILES['contents_file']['tmp_name']) && getimagesize($_FILES['contents_file']['tmp_name']) != false) {
    //         $imageInformation = getimagesize($_FILES['contents_file']['tmp_name']);
    //         // 이미지 정보 초기화
    //         $imgReadBinary = fopen($_FILES['contents_file']['tmp_name'], 'rb'); // rb, 파일 바이너리로 읽기
    //         $width = $imageInformation[0];
    //         $height = $imageInformation[1];
    //         $type = $imageInformation['mime']; //파일 mime-type; ex # "image/jpeg"
    //         $size = $_FILES['contents_file']['size'];
    //         $name = $_FILES['contents_file']['name'];   
    //         $createdAt = date('Y-m-d H:i:s');
    //         $maxSize = 8388608; // 8메가
            
    //         // 파일 사이즈 체크(제한 크기 보다 작을때 DB 저장)
    //         if ($size < $maxSize ) {
    //             $connectDB = connectDB(); // DB 연결
                
    //             // 데이터베이스 image 테이블 이미지 저장    
    //             $createImageStatement = $connectDB->prepare("INSERT INTO image (image, width, height, size, createdAt, fileName) VALUES (:image ,:width, :height, :size, :createdAt, :fileName)");

    //             $createImageStatement->bindParam(':image', $imgReadBinary, PDO::PARAM_LOB);
    //             $createImageStatement->bindParam(':width', $width, PDO::PARAM_INT);
    //             $createImageStatement->bindParam(':height', $height, PDO::PARAM_INT);
    //             $createImageStatement->bindParam(':size', $size, PDO::PARAM_INT);
    //             $createImageStatement->bindParam(':createdAt', $createdAt);
    //             $createImageStatement->bindParam(':fileName', $name);
    //             $createImageStatement->execute();
    //             // 저장한 이미지의 imageId
    //             // blog 테이블에 게시글을 저장할때 imageId 를 함께 저장하기위해 return
    //             $imageId = $connectDB->lastInsertId();
    //             return $imageId;
    //         } else {
    //             // 이미지 제한 사이즈 초과
    //             throw new Exception("File Size Error");
    //         }
    //     } else {
    //         // 이미지 업로드 실패
    //         throw new Exception("Unsupported Image Format!");
    //     }
    // }
?>