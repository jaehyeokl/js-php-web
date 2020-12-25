<?php
    // 블로그 게시글 작성 중 (write_post.php)
    // TODO: 주석 설명
    if ($_FILES['file']['name']) {
        if (!$_FILES['file']['error']) {
            // 파일 이름
            $name = md5(rand(100, 200));
            $ext = explode('.', $_FILES['file']['name']);
            $filename = $name . '.' . $ext[1];
            // 전달받은 파일을 지정한 위치에 저장            
            $destination = './img/post/' . $filename;
            $uploadedFile = $_FILES["file"]["tmp_name"];
            move_uploaded_file($uploadedFile, $destination);
            echo 'img/post/' . $filename;
            
            // TODO: 게시글 작성 중 에디터에 업로드 되었지만, 게시글이 업로드 되지 않은경우에는
            // 필요없는 이미지가 서버에 저장되어 있게됨, 어떻게 삭제를 해주어야 할지 생각해보자

            // 임시 경로에 저장된 파일을 반환하려고 해보았지만,
            // 삭제가 되기 때문에 에디터에서 보여줄 수 없는 것 같다
            // $fileName = $_FILES["file"]["tmp_name"];
            // $filePath = realpath($_FILES["file"]["tmp_name"]);
            // echo $filePath.$fileName;
            
        } else {
            echo  $message = 'Ooops! Your upload triggered the following error:  '.$_FILES['file']['error'];
        }
    }

?>