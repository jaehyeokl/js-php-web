<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="description" content="메인페이지"> -->
    <!-- <meta property="og:title" content="ego lego" /> -->
    <!-- <meta property="og:description" content="활동적인 아웃도어 라이프스타일" /> -->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    <title>LIVE</title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/live.css">
    <script src="js/common.js"></script>
    <!-- <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script> -->
    <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.11.1/video.min.js"></script>
    <!-- <script src="https://unpkg.com/videojs-flash/dist/videojs-flash.js"></script> -->
    <!-- <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-dash/4.0.0/videojs-dash.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/videojs-contrib-eme@3.7.0/dist/videojs-contrib-eme.min.js"></script>
    <!-- TODO: CDN 정리하기 -->

    <script src="http://cdn.dashjs.org/latest/dash.all.min.js"></script>

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

    <!-- Live Section -->
    <section id="live">
        <div class="live-header">
        <h1>라이브방송</h1>
        </div>
        <div class="live-body">
            <!-- <video id='video' width='696' height='392' class="video-js vjs-default-skin vjs-big-play-centered vjs-show-big-play-button-on-pause" controls autoplay="true" muted="muted" preload="none" data-setup='{"techorder" : ["flash", "html5"], "loop" : "true"}' loop> -->
            <!-- <source src="/video/streaming/test4.mpd" type="application/x-mpegURL">  -->
        </div>
    </section>

    <script>
        let player = videojs("video");
        // player.src({
        // src: "/video/streaming/test4.mpd",
        // type: "application/dash+xml",
        // });

        // player.eme();
        // player.src ({
        //     src: "/video/project/dash/stream.mpd",
        //     type: "application/dash+xml",
        //     keySystemOptions: [{
        //         name: 'com.widevine.alpha',
        //         options: {
        //             serverURL: 'http://m.widevine.com/proxy'
        //         }  
        //     }]
        // });


        player.ready(function(){
            player.src({
                'src': '/video/project/dash/stream.mpd',
                'type': 'application/dash+xml',
                'keySystemOptions': [
                    {
                        'name': 'com.widevine.alpha',
                        'options':{
                            'serverURL' : 'https://license.pallycon.com/ri/licenseManager.do',
                            // 'httpRequestHeaders' : {
                            //     'CUSTOM_HEADER_NAME' : 'CUSTOM_HEADER_VALUE',
                            // }  
                        }
                    },
                    // {
                    //     'name': 'com.microsoft.playready',
                    //     'options':{
                    //         'serverURL' : 'PALLYCON_LA_URL',
                    //         'httpRequestHeaders' : {
                    //             'CUSTOM_HEADER_NAME' : 'CUSTOM_HEADER_VALUE',
                    //         }
                    //     }
                    // }
                ]
            });
        })
        player.play();



    </script>
    <!-- End Live Section -->

</body>