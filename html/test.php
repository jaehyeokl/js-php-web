<?php
    // $content = '<p><img src="img/editor_tmp/069059b7ef840f0c74a814ec9237b6ec.png"><img src="img/editor_tmp/85d8ce590ad8981ca2c8286f79f59954.jpg"></p><p><br></p><p>수정된 경로로 이미지를 불러오는지 확인</p>'; 
    // $array1 = array();
    // $array2 = array();

    // array_push($array1, 'img/editor_tmp/069059b7ef840f0c74a814ec9237b6ec.png');
    // array_push($array1, 'img/editor_tmp/85d8ce590ad8981ca2c8286f79f59954.jpg');

    // array_push($array2, 'img/post/069059b7ef840f0c74a814ec9237b6ec.png');
    // array_push($array2, 'img/post/85d8ce590ad8981ca2c8286f79f59954.jpg');
    // /var/www/portfolio/html/img/post/069059b7ef840f0c74a814ec9237b6ec.png

    // print_r($array1);
    // print_r($array2);



    // echo $content;
    // preg_replace($array1, $array2, $content);
    // echo $content;

    // printf($content);

    // echo strip_tags( $content, '<p>');

    // $text = '<img src="img/post/82aa4b0af34c2313a562076992e50aa3.png">';
    // $array1 = array();
    // $array2 = array();

    // array_push($array1, 'img/post/82aa4b0af34c2313a562076992e50aa3.png');
    // array_push($array2, 'img/editor_tmp/069059b7ef840f0c74a814ec9237b6ec.png');
    // preg_replace($array1, $array2, $text);

    // // echo '<img src="img/post/82aa4b0af34c2313a562076992e50aa3.png">';
    // echo $text;

    $a = '<p><img src="img/editor_tmp/0a09c8844ba8f0936c20bd791130d6b6.png"><img src="img/editor_tmp/84d9ee44e457ddef7f2c4f25dc8fa865.jpg"><br></p>';


    echo str_replace("editor_tmp", "post", $a);





                    // print_r($imgSrcList);
                    // print_r($uploadImgSrcList);

?>