<?php
    // 블로그 게시글 페이징처리하여 불러오기

    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결

    // 페이지 url) www.website/blog.php?page=(현재 page)

    // 게시글 페이징처리 위한 변수 초기화 (페이지당 게시글 수, 전체 게시글 수)
    // 이를 통한 게시글 전체 페이지 수 초기화 (페이지 버튼)
    $postCount = 10; // 페이지당 포스팅 개수
    // 삭제된 게시글 제외
    $getTotalPageStatement = $connectDB->query("SELECT * FROM blog WHERE deletedAt IS NULL");
    $totalPostCount = $getTotalPageStatement->rowCount(); // 전체 게시글 수
    $totalPageCount = getTotalPage($postCount, $totalPostCount); // 전체 페이지 수
    
    // url 에서 현재 페이지 가져오기(GET)
    // 파라미터가 없을때(블로그 게시판 처음 들어왔을때 -> 1페이지)
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    // echo $page."<br>";
    // echo $totalPostCount."<br>";
    // echo $totalPageCount."<br>";

    // 현재 페이지에 보여줄 게시글 데이터 불러오기
    // 삭제된 게시글을 제외 / id 역순으로 불러오기(최신순) / 현재페이지에 들어갈 데이터만(페이징)
    $getPagingPostStatement = $connectDB->prepare("SELECT * FROM blog WHERE deletedAt IS NULL ORDER BY id DESC LIMIT :pagingStartPoint, :postCount");
    // 페이징 처리를 위한 주요 SQL문 - [LIMIT 시작위치, 불러올 개수]
    // 시작위치(테이블의 row) 에서부터 불러올 개수만큼 데이터를 불러온다
    // 이를 위해 시작위치를 구해준다
    $pagingStartPoint = getPagingStartPoint($postCount, $page);
    // prepare statement 에 바인드될 변수가 정수일때는 PDO::PARAM_INT 인자에 넣어주어야한다
    $getPagingPostStatement->bindParam(':pagingStartPoint', $pagingStartPoint, PDO::PARAM_INT);
    $getPagingPostStatement->bindParam(':postCount', $postCount, PDO::PARAM_INT);
    $getPagingPostStatement->execute();

    // 불러온 데이터를 HTML 
    // 불러온 게시글을 테이블에 반영한다
    while ($blogPostRow = $getPagingPostStatement->fetch()) {
        // 게시글의 작성자를 닉네임으로 표시하기 위해서
        // 게시글 데이터에 저장된 (creater)을 이용해 user 데이터에서 작성자의 닉네임을 불러온다
        // $name_statement = $connectDB->prepare("SELECT name FROM user WHERE email = :creater");
        // $name_statement->bindParam(':creater', $row['creater']);
        // $name_statement->execute();
        // $name_row = $name_statement->fetch();
        // $name = $name_row['name']; // 닉네임

        $postId = $blogPostRow['id']; // 게시글 id
        $title = $blogPostRow['title'];
        $contentsText = $blogPostRow['contentsText'];
        $formatCreatedAt = getFormatCreatedAt($blogPostRow); // 게시글 작성일

        
        $blogItemTag = $blogItemTag."<div class='blog-item'>".
                                        "<a href='view_post.php?id=$postId'>".
                                            "<div class='item-text'>".
                                                "<span>$title</span>".
                                                "<span>$contentsText</span>".
                                            "</div>".
                                            "<div class='item-img'><img src='' alt=''></div>".
                                        "</a>".
                                    "</div>";

        // echo $blogItemTag."<br>";

        // $listId = "<td class='index'>{$row['id']}</td>";
        // // <a> 태그의 링크에 게시글의 id를 파라미터로 추가한다
        // // 게시글이 고유한 주소를 가지게 하면서, id와 일치하는 게시글의 데이터만을 가져오기 위해
        // $listTitle = "<td class='title'><a href='view_post.php?id={$row['id']}'>{$row['title']}</a></td>";
        // $listCreater = "<td class='creater'>{$name}</td>";
        // $listCreated = "<td class='created'>{$time_created}</td>";
        // $listHit = "<td class='created'>{$row['hit']}</td>";

        // $totalRow = $totalRow."<tr>".$listId.$listTitle.$listCreater.$listCreated.$listHit."<tr>";
    }




    // 게시글 전체 페이지 수 구하기
    function getTotalPage($postCount, $totalPostCount) {
        $totalPageCount = (int) ($totalPostCount / $postCount) + 1;
        return $totalPageCount;
    }

    // DB 테이블에서 페이징 시작할 위치(row) 구하기
    function getPagingStartPoint($postCount, $page) {
        $pagingStartPoint = ($page - 1)*$postCount;
        return $pagingStartPoint;
    }

    // 게시글 작성날짜 포맷
    // default : 년.월.일 / today : 시:분
    function getFormatCreatedAt($row) {
        $currntDate = date("Y.m.d"); // 현재 날짜
        if($currntDate === $formatCreatedAt) {
            // 게시글 생성일이 현재 날짜와 같을 경우에는 시:분 으로 작성일을 표기한다
            $formatCreatedAt = date("H:i", strtotime($row['created']));
        } else {
            $formatCreatedAt = date("Y.m.d", strtotime($row['created']));
        }
        return $formatCreatedAt;
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="description" content="메인페이지"> -->
    <!-- <meta property="og:title" content="ego lego" /> -->
    <!-- <meta property="og:description" content="활동적인 아웃도어 라이프스타일" /> -->
    <title>Blog</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/blog.css">
    <!-- <script src="https://kit.fontawesome.com/8451689280.js" crossorigin="anonymous"></script> -->
</head>
<body>
    <!-- Header -->
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="nav-list">
                    <ul>
                        <li><a href="#main">Home</a></li>
                        <li><a href="#projects">Projects</a></li>
                        <li><a href="#blog">Blog</a></li>
                        <!-- <li><a href="#about">About</a></li> -->
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header -->

    <!-- Blog -->
    <section id="blog">
        <div class="blog header">
            <div class="blog-title">
                <h1>Blog</h1>
            </div>
            <a href="write_post.php">글작성</a>
        </div>
        <div class="blog body">
            <!-- 블로그 아이템 태그 -->
            <?= $blogItemTag ?>

            <!-- <div class="blog-item">
                <a href="view_post.php">
                    <div class="item-text">
                        <span>제목</span>
                        <span>내용 내용내용내용</span>
                    </div>
                    <div class="item-img"><img src="" alt=""></div>
                </a>
            </div>

            <div class="blog-item">
                <a href="">
                    <div class="item-text">
                        <span>제목</span>
                        <span>내용 내용내용내용</span>
                    </div>
                    <div class="item-img"><img src="../img/layout/background-main.jpg" alt=""></div>
                </a>
            </div> -->
        </div>
    </section>
    <!-- End Blog -->
    
</body>