<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
    $connectDB = connectDB(); // DB 연결
    logVisitor(); // 방문 로그 체크 및 저장

    // 페이지 접근 권한 체크
    // if (!$signinSessionStatus[2] == 1) {
    //     // 관리자 계정이 아닐때 메인페이지로 이동
    //     // (비로그인 또는 로그인한 계정이 관리자권한 '1' 을 가지고 있지 않을때)
    //     header("Location: https://jaehyeok.ml/");
    //     die();
    // } 

    // 오늘 방문자 수
    $today = date('Y-m-d');
    
    try {
        $getTodayVisitStatement = $connectDB->prepare("SELECT * FROM visitLog WHERE DATE(visitedAt) = :today");
        $getTodayVisitStatement->bindParam(':today', $today, PDO::PARAM_STR);
        $getTodayVisitStatement->execute();
        $todayVisitCount = $getTodayVisitStatement->rowCount(); // 오늘 방문자 수
        
    } catch (PDOException $ex) {
        echo "failed! : ".$ex->getMessage()."<br>";
    }
    

    // 월별 방문자
    // 월별 방문자수를 array 에 저장하여 javascript 에서도 array 그대로 사용할 수 있도록 전달한다
    $montlyVisitCountArray = array();

    try {
        // 1월부터 12월까지 월별 방문자 구하기
        for ($i = 1; $i <= 12; $i++) {
            $montlyFirstDay = date('Y-'.$i.'-01'); // 매월 1일
            $montlyLastDay = date('Y-'.$i.'-t'); // 매월 마지막 일

            $getMontlyVisitStatement = $connectDB->prepare("SELECT * FROM visitLog WHERE DATE(visitedAt) BETWEEN :montlyFirstDay AND :montlyLastDay");
            $getMontlyVisitStatement->bindParam(':montlyFirstDay', $montlyFirstDay);
            $getMontlyVisitStatement->bindParam(':montlyLastDay', $montlyLastDay);
            $getMontlyVisitStatement->execute();
            $montlyVisitCount = $getMontlyVisitStatement->rowCount(); // 월별 방문자 수

            // echo $montlyFirstDay;
            // echo "<br>";
            // echo $montlyLastDay;
            // echo "<br>";
            // echo $montlyVisitCount;
            // echo "<br>";

            array_push($montlyVisitCountArray, $montlyVisitCount);
        }
    } catch (PDOException $ex) {
        echo "failed! : ".$ex->getMessage()."<br>";
    }


    // 방문 브라우저 비율
    // javascript chart.js 에서 사용할 수 있도록 배열을 만들어서 전달한다
    $browserArray = array(); // 브라우저 이름 저장할 배열
    $browserCountArray = array(); // 브라우저 개수 저장할 배열

    try {
        $getMontlyVisitStatement = $connectDB->prepare("SELECT browser, COUNT(*) FROM visitLog GROUP BY browser");
        $getMontlyVisitStatement->execute();
        
        while ($browserCountRow = $getMontlyVisitStatement->fetch()) {

            array_push($browserArray, $browserCountRow[0]);
            array_push($browserCountArray, $browserCountRow[1]);
        }
    } catch (PDOException $ex) {
        echo "failed! : ".$ex->getMessage()."<br>";
    }


    // 방문 국가별 비율
    $countryArray = array(); // 국가명 저장할 배열
    $countryCountArray = array(); // 국가별 방문 횟수 저장할 배열

    try {
        $getCountryVisitStatement = $connectDB->prepare("SELECT country, COUNT(*) FROM visitLog GROUP BY country");
        $getCountryVisitStatement->execute();
        
        while ($countyCountRow = $getCountryVisitStatement->fetch()) {

            array_push($countryArray, $countyCountRow[0]);
            array_push($countryCountArray, $countyCountRow[1]);
        }
    } catch (PDOException $ex) {
        echo "failed! : ".$ex->getMessage()."<br>";
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
    <title>Manager</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/manager.css">
    <script src="js/common.js"></script>
    <!-- <script src="../resources/library/Chart.js-2.9.4/dist/Chart.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"></script> -->
</head>
<body>
    <!-- Header -->
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="nav-list">
                    <ul class="nav-menu">
                        <li><a href="index.php">Home</a></li>
                    </ul>
                    <ul class="nav-manager" id="<?php echo $signinSessionStatus[2];?>">
                        <li class="manager-button">관리</li>
                        <ul class="manager-menu">
                            <li><a href="write_post.php">게시글 작성</a></li>
                            <li><a href="manager.php">관리자페이지</a></li>
                            <li><a href="logout.php">로그아웃</a></li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header -->

    <!-- Chart Section  -->
    <section id="chart">
        <div class="chart header">
            <h1>방문자 통계</h1>
        </div>
        <div class="chart body">
            <div class="chart-item">
                <div class="item-header">
                    <h4>오늘 방문자</h4>
                </div>
                <p><?=$todayVisitCount?></p>
            </div>
            <div class="chart-item">
                <div class="item-header">
                    <h4>월별 방문자 통계</h4>
                    <!-- <span>년도선택</span> -->
                </div>
                <canvas class="montly-visit"></canvas>
            </div>
            <div class="chart-item">
                <div class="item-header">
                    <h4>접속 브라우저 비율</h4>
                </div>
                <canvas class="browser"></canvas>
            </div>
            <div class="chart-item">
                <div class="item-header">
                    <h4>방문 국가 비율</h4>
                </div>
                <canvas class="counrty"></canvas>
            </div>
        </div>
    </section>

    <script>

        // 월별 방문자 통계 chart
        // PHP 배열을 그대로 사용할 수 있다
        var montlyVisitCountArray = <?php echo json_encode($montlyVisitCountArray);?>;

        console.log(montlyVisitCountArray);
        var montlyVisit = document.querySelector('.montly-visit');
        var myChart = new Chart(montlyVisit, {
            type: 'line',
            data: {
                labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                datasets: [{
                    label: '월별 총 방문자 수',
                    data: montlyVisitCountArray,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
            }
        });

        // 브라우저 비율 chart
        var browserArray = <?php echo json_encode($browserArray);?>;
        var browserCountArray = <?php echo json_encode($browserCountArray);?>;

        var browser = document.querySelector('.browser');
        var myPieChart = new Chart(browser, {
            type: 'pie',
            data: {
                // labels: ["Chrome", "Firefox", "IE", "Edge", "Safari"],
                labels: browserArray,
                datasets: [{
                    data: browserCountArray,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    // hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: { 
                responsive: true, 
                legend: false, 
                maintainAspectRatio : false, 
                animation: false, 
                pieceLabel: { 
                    mode:"label", 
                    position:"outside", 
                    fontSize: 11, 
                    fontStyle: 'bold' 
                }
            }
        });

        // 방문 국가별 비율 chart
        var countryArray = <?php echo json_encode($countryArray);?>;
        var countryCountArray = <?php echo json_encode($countryCountArray);?>;

        var ctx = document.querySelector('.counrty');
        var myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: countryArray,
                datasets: [{
                    data: countryCountArray,
                    backgroundColor: [
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    // hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: { 
                responsive: true, 
                legend: false, 
                maintainAspectRatio : false, 
                animation: false, 
                pieceLabel: { 
                    mode:"label", 
                    position:"outside", 
                    fontSize: 11,
                    fontStyle: 'bold' 
                }
            }
        });

    </script>
    <!-- End Main Section  -->

    

    <!-- Footer Section -->
    <!-- <section id="footer">
        <div class="footer">
            <a href="signin.php">관리자 로그인</a>
            <span>@Designed By JaeHyeok</span>
        </div>
    </section> -->
    <!-- End Footer Section -->
</body>
</html>