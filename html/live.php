<?php
    define("IV","0123456789abcdef");
    // $siteId = "";
    // $siteKey = "";
    // $accessKey = "";
    include_once("../resources/drm_config.php");


    
    $policyDetail = array (
        'policy_version' => 2,
        'playback_policy' => array (
            'persistent' => false, // 오프라인 라이센스 적용 X
            'license_duration' => 0,  // 300
            // 'expire_date' => '<yyyy-mm-ddThh:mm:ssZ 형식의 만료 시간(GMT)>', // 위의 license_duration 과 함께 사용할 수 없다
            // 'rental_duration' => 1,
            // 'playback_duration' => 1,
            'allowed_track_types' => 'ALL', // ??
        ),
        'security_policy' => array (
            0 => array (
                'track_type' => 'ALL',
                'widevine' => array (
                    'security_level' => 1,
                    'required_hdcp_version' => 'HDCP_NONE',
                    // 'required_cgms_flags' => 'CGMS_NONE',
                    // 'disable_analog_output' => false,
                    // 'hdcp_srm_rule' => 'HDCP_SRM_RULE_NONE',
                ),
                // 'playready' => array (
                    //     'security_level' => 150,
                    //     'digital_video_protection_level' => 100,
                    //     'analog_video_protection_level' => 100,
                    //     'digital_audio_protection_level' => 100,
                    //     'require_hdcp_type_1' => true,
                    // ),
                    // 'fairplay' => array (
                        //     'hdcp_enforcement' => -1,
                        //     'allow_airplay' => true,
                        //     'allow_av_adapter' => true,
                        // ),
                        // 'ncg' => array (
                            //     'allow_mobile_abnormal_device' => true,
                            //     'allow_external_display' => true,
                            //     'control_hdcp' => 0,
                            // ),
                        ),
                    ),
                    // 'external_key' => array (
                        //     'mpeg_cenc' => array (
                            //         0 => array (
                                //             'track_type' => 'ALL',
                                //             'key_id' => '<hex-string>',
                                //             'key' => '<hex-string>',
                                //             'iv' => '<hex-string>',
                                //         ),
                                //     ),
                                //     'hls_aes' => array (
                                    //         0 => array (
                                        //             'track_type' => 'ALL',
                                        //             'key' => '<hex-string>',
                                        //             'iv' => '<hex-string>',
                                        //         ),
                                        //     ),
                                        //   'ncg' => array (
                                            //         'cek' => '<hex-string>',
                                            //     ),
                                            // ),
    );

    // 정책 생성
    $policy = array (
        'policy_version' => 2,
        'playback_policy' => 
        array (
            'persistent' => false,
            'license_duration' => 0,
            'rental_duration' => 0,
            'playback_duration' => 0,
            'allowed_track_types' => 'ALL',
        ),
        'security_policy' => 
        array (
            0 => 
            array (
                'track_type' => 'ALL',
                'widevine' => 
                array (
                    'security_level' => 2,
                    'required_hdcp_version' => 'HDCP_NONE',
                    'required_cgms_flags' => 'CGMS_NONE',
                    'disable_analog_output' => false,
                    'hdcp_srm_rule' => 'HDCP_SRM_RULE_NONE',
                ),
                'playready' => 
                array (
                    'security_level' => 150,
                    'digital_video_protection_level' => 100,
                    'analog_video_protection_level' => 100,
                    'digital_audio_protection_level' => 100,
                    'require_hdcp_type_1' => false,
                ),
                'fairplay' => 
                array (
                    'hdcp_enforcement' => -1,
                    'allow_airplay' => true,
                    'allow_av_adapter' => true,
                ),
                'ncg' => 
                array (
                    'allow_mobile_abnormal_device' => false,
                    'allow_external_display' => false,
                    'control_hdcp' => 0,
                ),
            ),
        ),
    );

    // $c = json_encode($policy, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    // var_dump($c);

    $samplePolicy = array (
        'playback_policy' => 
        array (
          'limit' => true,
          'persistent' => false,
          'duration' => 3600,
        ),
    );

    $samplePolicy2 = array (
        'policy_version' => 2,
    );
    
    $policyString = openssl_encrypt(json_encode($policy), "AES-256-CBC", $siteKey, 0, IV);
    
                                        
    // 정책을 AES256 으로 암호화
    // $policyAES256 = openssl_encrypt(json_encode($policy), "AES-256-CBC", $siteKey, 0, IV);
    // 결과를 Base 64 문자열로 변환해야한다
    // $policyString = base64_encode($policyAES256);
    // $policyString = openssl_encrypt(json_encode($policy), "AES-256-CBC", $siteKey, 0, IV);
    // echo $policyAES256;
    // echo $policyString;


    // 해쉬생성
    $drmType = "Widevine";
    $userId = "LICENSETOKEN"; // 없을경우의 default 값
    $cid = "test4";
    $timestamp = gmdate("Y-m-d\Th:i:s\Z");

    // echo "<br>";
    // echo $accessKey;
    // echo "<br>";
    // echo $drmType;
    // echo "<br>";
    // echo $siteId;
    // echo "<br>";
    // echo $userId;
    // echo "<br>";
    // echo $cid;
    // echo "<br>";
    // echo $policyString;
    // echo "<br>";
    // echo $timestamp;
    // echo "<br>";
    $body = $accessKey.$drmType.$siteId.$userId.$cid.$policyString.$timestamp;
    $hashString = base64_encode(hash("sha256", $body, true));


    // echo "<br>";
    // echo $hashString;

    // 토큰생성
    $tokenData = array (
        'drm_type' => $drmType,
        'site_id' => $siteId,
        'user_id' => $userId,
        'cid' => $cid,
        'token' => $policyString,
        'timestamp' => $timestamp,
        'hash' => $hashString,
        
    );
    // 'response_format' => 'original',
    // 'key_rotation' => false

    $token = base64_encode(json_encode($tokenData));
    // echo $token;

    // $token = base64_encode(json_encode(
    //     ["drm_type"=> $drmType,
    //      "site_id"=> $this->_siteId, 
    //      "user_id"=> $this->_userId, 
    //      "cid"=> $this->_cid, 
    //      "policy"=> $this->_encPolicy, 
    //      "timestamp"=> $this->_timestamp, 
    //      "response_format"=> $this->_responseFormat, 
    //      "hash"=> $this->_hash]
    // ));
    // $result = base64_encode(json_encode([$drmType, $siteId, $userId, $cid, $policyString, $timestamp, "original", $hash]));
    // echo "<br>";
    // echo $result;
?>

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
    <!-- <script src="https://unpkg.com/videojs-flash/dist/videojs-flash.js"></script> -->
    <!-- <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script> -->
    <!-- TODO: CDN 정리하기 -->

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.11.1/video.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/6.12.0/video.min.js"></script>
    <!-- <script src="http://cdn.dashjs.org/latest/dash.all.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dashjs/3.2.0/dash.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-dash/2.11.0/videojs-dash.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-dash/4.0.0/videojs-dash.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/videojs-contrib-eme@3.7.0/dist/videojs-contrib-eme.min.js"></script> -->
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
            <video id='video' width='696' height='392' class="video-js vjs-default-skin vjs-big-play-centered vjs-show-big-play-button-on-pause" controls autoplay="true" muted="muted" preload="none" data-setup='{"techorder" : ["flash", "html5"], "loop" : "true"}' loop>
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

        
        const token = '<?= $token ?>';

        // player.eme();
        // console.log(token);
        // dictionary MediaKeySystemMediaCapability {
        //     DOMString contentType = "";
        //     DOMString robustness = "";
        // };

        player.ready(function(){
            player.src({
                'src': '/video/project/dash/stream.mpd',
                'type': 'application/dash+xml',
                'keySystemOptions': [
                    {
                        'name': 'com.widevine.alpha',
                        'options':{
                            'serverURL' : 'https://license.pallycon.com/ri/licenseManager.do',
                            'httpRequestHeaders' : {
                                'pallycon-customdata-v2' : token,
                            }
                        }
                    },
                    {
                        'name': 'com.microsoft.playready',
                        'options':{
                            'serverURL' : 'https://license.pallycon.com/ri/licenseManager.do',
                            'httpRequestHeaders' : {
                                'pallycon-customdata-v2' : token,
                            }
                        }
                    }
                ]
            });
        })
        player.play();



    </script>
    <!-- End Live Section -->

</body>