<?php
    // 블로그 게시글 목록
    // 게시글 페이징처리하여 불러오기
    // url) www.jaehyeok.ml/blog.php?page=(현재 page)

    include_once("../resources/config.php");
    $connectDB = connectDB(); // DB 연결
    
    /** 변수 초기화 **/

    // 페이징 작업을 위한 변수
    $postCount = 10; // 페이지당 포스팅 개수
    $getTotalPageStatement = $connectDB->query("SELECT * FROM blog WHERE deletedAt IS NULL");
    $totalPostCount = $getTotalPageStatement->rowCount(); // 전체 게시글 수
    $totalPageCount = getTotalPage($postCount, $totalPostCount); // 전체 페이지 수
    // url 에서 현재 블로그 페이지 가져오기(GET)
    // 파라미터가 없을때(블로그 게시판 처음 들어왔을때 -> 1페이지)
    $page = $_GET['page'] == '' ? 1 : $_GET['page'];

    // 하단 페이지 버튼 생성 위한 변수
    $blockPageCount = 3; // 한번에 보여줄 버튼 개수(블록)
    $totalBlockCount = ceil($totalPageCount / $blockPageCount); // 총 블록
    $currentBlock = ceil($page/$blockPageCount); // 현재 페이지가 속한 블록
    // 블럭의 첫번째 페이지 번호
    // 게시글이 부족하여 블럭이 없을 경우에도 1페이지는 표시한다
    $blockStartPage = ($currentBlock * $blockPageCount) - ($blockPageCount - 1);
    if ($blockStartPage <= 1) {
        $blockStartPage = 1;
    }

    $blockEndPage = $currentBlock*$blockPageCount;
    if ($totalPageCount <= $blockEndPage) {
        $blockEndPage = $totalPageCount;
    }


    /* 하단 페이지 버튼 생성*/

    // 현재 블록의 페이지 버튼 생성
    for ($p = $blockStartPage; $p <= $blockEndPage; $p++) {
        $buttonNumber = $p;
        $pageButtonTag = $pageButtonTag.'<a href="blog.php?page='.$buttonNumber.'">'.$buttonNumber.'</a>';
    }

    // 블록 이전/다음 이동 버튼 생성
    $previousStartPage = $blockStartPage - $blockPageCount;
    $nextBlockStartPage = $blockStartPage + $blockPageCount;

    if ($currentBlock == 1) {
        // 첫 블록일 경우에는 다음블록 이동 버튼만 생성
        $nextBlockTag = '<a class="next-block" href="blog.php?page='.$nextBlockStartPage.'">'.'>'.'</a>';
    } else if ($currentBlock == $totalBlockCount) {
        // 마지막 블록일 경우에는 이전블록 이동 버튼만 생성
        $previousBlockTag = '<a class="previous-block" href="blog.php?page='.$previousStartPage.'">'.'<'.'</a>';
    } else {
        // 나머지 이전 다음 블록 생성
        $previousBlockTag = '<a class="previous-block" href="blog.php?page='.$previousStartPage.'">'.'<'.'</a>';
        $nextBlockTag = '<a class="next-block" href="blog.php?page='.$nextBlockStartPage.'">'.'>'.'</a>';
    }

    
    /* 게시글 불러오기 */

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

    // 불러온 데이터를 통해 게시글 HTML 태그 구현
    while ($blogPostRow = $getPagingPostStatement->fetch()) {
        // TODO: 작성자 불러올경우에는 JOIN 을 통하여 한번의 쿼리문으로 함께 불러올 수 있도록 하자
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

        // 블로그 게시글 태그 생성
        $blogItemTag = $blogItemTag."<div class='blog-item'>".
                                        "<a href='view_post.php?id=$postId'>".
                                            "<div class='item-text'>".
                                                "<span>$title</span>".
                                                "<span>$contentsText</span>".
                                            "</div>".
                                            "<div class='item-img'>".
                                                // "<img src='' alt=''>".
                                            "</div>".
                                        "</a>".
                                    "</div>";

        // TODO: 이미지 또는 비디오일 경우에는 어떻게 태그를 추가할 것인가
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
                        <li><a href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="blog-title">
            <a href="blog.php">BLOG</a>
        </div>
    </section>
    <!-- End Header -->

    <!-- Blog -->
    <section id="blog">
        <div class="blog header">
            <!-- <div class="blog-title">
                <h1>Blog</h1>
            </div> -->
            <a href="">뒤로</a>
            <a href="write_post.php">글작성</a>
        </div>
        <div class="blog body">
            <!-- 블로그 아이템 태그 -->
            <?= $blogItemTag ?>
        </div>
        <div class="page_button">
            <?= $previousBlockTag ?>
            <?= $pageButtonTag ?>
            <?= $nextBlockTag ?>
        </div>
    </section>
    <!-- End Blog -->
    
</body>