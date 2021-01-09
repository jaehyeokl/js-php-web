<?php
    include_once("../resources/config.php");
    $signinSessionStatus = checkSigninStatus(); // 로그인 세션 확인
    $connectDB = connectDB(); // DB 연결
    logVisitor(); // 방문 로그 체크 및 저장



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
                <p>300</p>
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
                    <h4>접속 브라우저</h4>
                </div>
                <canvas class="browser"></canvas>
            </div>
            <div class="chart-item">
                <div class="item-header">
                    <h4>유입 경로</h4>
                </div>
                <canvas class="referer"></canvas>
            </div>
        </div>
    </section>

    <script>
        // 월별 방문자 통계 chart
        var montlyVisit = document.querySelector('.montly-visit');
        var myChart = new Chart(montlyVisit, {
            type: 'line',
            data: {
                labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                datasets: [{
                    label: '월별 총 방문자 수',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        // 'rgba(54, 162, 235, 0.2)',
                        // 'rgba(255, 206, 86, 0.2)',
                        // 'rgba(75, 192, 192, 0.2)',
                        // 'rgba(153, 102, 255, 0.2)',
                        // 'rgba(255, 159, 64, 0.2)'
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
        var browser = document.querySelector('.browser');
        var myPieChart = new Chart(browser, {
            type: 'pie',
            data: {
                labels: ["Chrome", "Firefox", "IE", "Edge", "Safari"],
                datasets: [{
                    data: [30, 50, 20, 10],
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

        // 유입 경로 비율 chart
        var ctx = document.querySelector('.referer');
        var myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Direct", "Referral", "Social"],
                datasets: [{
                    data: [30, 50, 20],
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