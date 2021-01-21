<?php 
    define("IV","0123456789abcdef"); // DRM policy(정책)을 암호화할때 사용되는 상수
    include_once("../resources/drm_config.php"); // PallyCon DRM key


    
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
                    'security_level' => 1,
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
    
    // 정책을 AES256 으로 암호화
    $policyString = openssl_encrypt(json_encode($policy), "AES-256-CBC", $siteKey, 0, IV);

    // 해쉬생성
    $drmType = "Widevine";
    $userId = "LICENSETOKEN"; // 없을경우의 default 값
    $cid = "test4";
    $timestamp = gmdate("Y-m-d\Th:i:s\Z");

    $body = $accessKey.$drmType.$siteId.$userId.$cid.$policyString.$timestamp;
    $hashString = base64_encode(hash("sha256", $body, true));


    // 라이센스 요청을 위한 토큰 생성
    // 앞에서 암호화한 정책과, 해쉬를 포함한 json 을 통해 생성한다
    $tokenData = array (
        'drm_type' => $drmType,
        'site_id' => $siteId,
        'user_id' => $userId,
        'cid' => $cid,
        'token' => $policyString,
        'timestamp' => $timestamp,
        'hash' => $hashString,
        'response_format' => 'original', // default
        'key_rotation' => false // default
    );

    $token = base64_encode(json_encode($tokenData));
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
    <!-- video.js CSS -->
    <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" /> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/6.12.0/video.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dashjs/3.2.0/dash.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-dash/2.11.0/videojs-dash.min.js"></script>
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
        <div class="live-left">
            <div class="left-header">
                <div class="header-title">
                    <h1>Live Streaming</h1>
                </div>
                <div class="header-auth">
                    <span class="auth-user">브라우저 정보, DRM 정보, 등</span>
                    <div class="auth-guide">
                        <span class="auth-message">라이브 시청을 위해서는 이메일 인증이 필요합니다</span>
                        <a class="auth-button" href="#">인증하기</a>
                    </div>
                </div>
            </div>
            <div class="left-player">
                <video id='video'  class="video-js vjs-default-skin vjs-big-play-centered vjs-show-big-play-button-on-pause" controls autoplay="true" muted="muted" preload="none" data-setup='{"techorder" : ["flash", "html5"], "loop" : "true"}' loop>
            </div>
        </div>
        <div class="live-right">
            <div class="body-chat">
                <div>
                    채팅 필드
                </div>
            </div>
        </div>
    </section>

    <script>
        // 이메일 인증 이후, 라이센스 얻기
        // 라이센스를 발급하기위해 필요한 정보를 제공해 주어야한다
        // drmType 은 브라우저 현재 접속한 브라우저를 통해 초기화

        let drmType;
        const agent = navigator.userAgent.toLowerCase(); 
        if (agent.indexOf("chrome") != -1) drmType = "Widevine";
        if (agent.indexOf("firefox") != -1) drmType = "Widevine";
        if (agent.indexOf("edge") != -1) drmType = "PlayReady";
        if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ) {
            drmType = "PlayReady";
        }
        // if (agt.indexOf("webtv") != -1) return 'WebTV'; 
        // if (agt.indexOf("beonex") != -1) return 'Beonex'; 
        // if (agt.indexOf("chimera") != -1) return 'Chimera'; 
        // if (agt.indexOf("netpositive") != -1) return 'NetPositive'; 
        // if (agt.indexOf("phoenix") != -1) return 'Phoenix'; 
        // if (agt.indexOf("safari") != -1) return 'Safari'; 
        // if (agt.indexOf("skipstone") != -1) return 'SkipStone'; 
        // if (agt.indexOf("netscape") != -1) return 'Netscape'; 
        // if (agt.indexOf("mozilla/5.0") != -1) return 'Mozilla'; 

        let licenseToken;
        let value = { "drmType": drmType, 
            userId: "hyukzza@naver.com",
            contentId: "test4",
        }
        // TODO: 값 지정할 수 있도록 설정,
        // TODO: DB 설정하여 방송 정보 저장, 사용자 라이브 시청 정보 저장

        fetch("generate_drm_token.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(value),
        })
            .then(response => response.json())
            .then(data => {
                licenseToken = data.licenseToken
                // console.log(licenseToken)
            })
            .catch((error) => console.log(error))

    
        
        // 플레이에어서 라이센스 DRM 연동하기!
        let player = videojs("video");
        
        const token = '<?= $token ?>';

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
                                'pallycon-customdata-v2' : licenseToken,
                                // 'pallycon-customdata-v2' : token,
                            }
                        }
                    },
                    {
                        'name': 'com.microsoft.playready',
                        'options':{
                            'serverURL' : 'https://license.pallycon.com/ri/licenseManager.do',
                            'httpRequestHeaders' : {
                                'pallycon-customdata-v2' : licenseToken,
                                // 'pallycon-customdata-v2' : token,
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