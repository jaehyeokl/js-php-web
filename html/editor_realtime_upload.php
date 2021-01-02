<?php
    // 블로그 게시글 작성 중 (write_post.php)
    // summernote editor 에 업로드되는 이미지, 비디오 파일을 서버의 임시디렉토리에 저장한다
    // 이미지 업로드일때는 $_FILES['image']
    // 비디오 업로드일때는 $_FILES['video'] 으로 파일을 전달받는다

    // 이미지 업로드 ($_FILES['image'])
    if ($_FILES['image']['name']) {
        if (!$_FILES['image']['error']) {
            
            $name = md5(rand(100, 200));  // 파일명
            $ext = explode('.', $_FILES['image']['name']);
            $filename = $name . '.' . $ext[1];
            // 전달받은 파일을 지정한 위치에 저장            
            $destination = './img/editor_tmp/' . $filename;
            $uploadedFile = $_FILES["image"]["tmp_name"];
            move_uploaded_file($uploadedFile, $destination);
            echo 'img/editor_tmp/' . $filename;
        
        } else {
            echo  $message = 'Ooops! Your upload triggered the following error:  '.$_FILES['image']['error'];
        }
    }

    // 동영상 업로드 ($_FILES['video'])
    if ($_FILES['video']['name']) {
        if (!$_FILES['video']['error']) {

            $name = md5(rand(100, 200)); // 파일명
            $ext = explode('.', $_FILES['video']['name']);
            $filename = $name . '.' . $ext[1];
            // 전달받은 파일을 지정한 위치에 저장            
            $destination = './video/editor_tmp/' . $filename;
            $uploadedFile = $_FILES["video"]["tmp_name"];
            move_uploaded_file($uploadedFile, $destination);
            echo 'video/editor_tmp/' . $filename;    
            
        } else {
            // echo  $message = 'Ooops! Your upload triggered the following error:  '.$_FILES['video']['error'];
            echo $_FILES['video']['error'];
        }
    }
?>