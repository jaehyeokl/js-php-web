<?php
    define("IV","0123456789abcdef");
    // $siteId = "";
    // $siteKey = "";
    // $accessKey = "";
    include_once("../resources/drm_config.php");


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

    // $c = json_encode($policy, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    // var_dump($c);
    
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

    $samplePolicy = array (
        'playback_policy' => 
        array (
          'limit' => true,
          'persistent' => false,
          'duration' => 3600,
        ),
    );
    
    $policyString = openssl_encrypt(json_encode($samplePolicy), "AES-256-CBC", $siteKey, 0, IV);


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

<!doctype html>
<html>
    <head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
        <title>Dash.js Rocks</title>
        <style>
            video {
                width: 640px;
                height: 360px;
            }
        </style>
        <!-- <script src="http://cdn.dashjs.org/latest/dash.all.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/3.0.7/shaka-player.compiled.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/3.0.7/shaka-player.compiled.js" integrity="sha512-sLYxWqfGfOx9z9+KEuNjPMZEYfs2PrKjIizQobarfvJ2hHVDLVtD34C3zsKPeXgLR18PXHZG3C7p243O32JHGQ==" crossorigin="anonymous"></script>
        <!-- <script src="node_modules/eme-encryption-scheme-polyfill/dist/eme-encryption-scheme-polyfill.js"></script> -->
        <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    </head>
    <body>
        <div>
            <!-- <video id="videoPlayer" controls></video> -->
            <!-- <video data-dashjs-player autoplay controls src="/video/streaming/talkpic_test.mpd"> -->
                <!-- <source src="/video/streaming/audio.webm"> -->
            <!-- </video> -->
        </div>
        <!-- <script src="http://cdn.dashjs.org/latest/dash.all.min.js"></script> -->
        <!-- <script>
            (function(){
                var url = "/video/project/test1.mpd";
                var player = dashjs.MediaPlayer().create();
                player.initialize(document.querySelector("#videoPlayer"), url, true);
            })();
        </script> -->


        <video id="video" width="640" controls autoplay></video>

        <script>
            const manifestUrl = "/video/project/dash/stream.mpd";
            const result = '<?= $token ?>';
            // let array = <?php echo json_encode($tokenData);?>;
            // let result = btoa(array);

            shaka.polyfill.installAll();
            var video = document.getElementById('video');
            var player = new shaka.Player(video);
            window.player = player;

            player.load(manifestUrl).then(function(){}).catch(onError);

            // var serverCertificate = 'SERVER_CERTIFICATE';

            player.configure({
                drm:{
                    servers:{
                        'com.widevine.alpha': 'https://license.pallycon.com/ri/licenseManager.do',
                        'com.microsoft.playready': 'https://license.pallycon.com/ri/licenseManager.do'
                    },
                    advanced: {
                        'com.widevine.alpha': {
                            // serverCertificate: serverCertificate
                            'videoRobustness': 'SW_SECURE_CRYPTO',
                            'audioRobustness': 'SW_SECURE_CRYPTO'
                        }
                    }
                }
            });

            player.getNetworkingEngine().registerRequestFilter(function(type, request) {
                if (type == shaka.net.NetworkingEngine.RequestType.LICENSE) {
                    request.headers['pallycon-customdata-v2'] = result;
                }
            });

            function onError() {
                console.log("ERROR");
            }

            // function initApp(){
            //     shaka.polyfill.installAll();

            //     if(shaka.Player.isBrowserSupported()){
            //         initPlayer();
            //     } else{
            //         console.error("Browser not supported!");
            //     }
            // }

            // function initPlayer(){
            //     const video = document.getElementById('video');
            //     const player = new shaka.Player(video);

            //     window.player = player;

            //     player.addEventListener('error', onErrorEvent);

            //     player.load(manifestUrl).then(function(){
            //         console.log("The video has now been loaded!");
            //     }).catch(onError);
            // }

            // function onErrorEvent(event){
            //     onError(event.detail);
            // }

            // function onError(error){
            //     console.error("Error code", error.code, 'object', error);
            // }

            // document.addEventListener("DOMContentLoaded", initApp);


            // function base64DecodeUint8Array(input) {
            //     var raw = window.atob(input);
            //     var rawLength = raw.length;
            //     var array = new Uint8Array(new ArrayBuffer(rawLength));

            //     for(i = 0; i < rawLength; i++) {
            //         array[i] = raw.charCodeAt(i);
            //     }

            //     return array;
            // }

            // shaka.polyfill.installAll();
            // var video = document.getElementById('video');
            // var player = new shaka.Player(video);
            // window.player = player;

            // player.load('https://jaehyeok.ml/video/project/dash/stream.mpd').then(function(){}).catch(onError);

            // // var base64Cert = "CsECCAMSEBcFuRfMEgSGiwYzOi93KowYgrSCkgUijgIwggEKAoIBAQCZ7Vs7Mn2rXiTvw7YqlbWYUgrVvMs3UD4GRbgU2Ha430BRBEGtjOOtsRu4jE5yWl5KngeVKR1YWEAjp+GvDjipEnk5MAhhC28VjIeMfiG/+/7qd+EBnh5XgeikX0YmPRTmDoBYqGB63OBPrIRXsTeo1nzN6zNwXZg6IftO7L1KEMpHSQykfqpdQ4IY3brxyt4zkvE9b/tkQv0x4b9AsMYE0cS6TJUgpL+X7r1gkpr87vVbuvVk4tDnbNfFXHOggrmWEguDWe3OJHBwgmgNb2fG2CxKxfMTRJCnTuw3r0svAQxZ6ChD4lgvC2ufXbD8Xm7fZPvTCLRxG88SUAGcn1oJAgMBAAE6FGxpY2Vuc2Uud2lkZXZpbmUuY29tEoADrjRzFLWoNSl/JxOI+3u4y1J30kmCPN3R2jC5MzlRHrPMveoEuUS5J8EhNG79verJ1BORfm7BdqEEOEYKUDvBlSubpOTOD8S/wgqYCKqvS/zRnB3PzfV0zKwo0bQQQWz53ogEMBy9szTK/NDUCXhCOmQuVGE98K/PlspKkknYVeQrOnA+8XZ/apvTbWv4K+drvwy6T95Z0qvMdv62Qke4XEMfvKUiZrYZ/DaXlUP8qcu9u/r6DhpV51Wjx7zmVflkb1gquc9wqgi5efhn9joLK3/bNixbxOzVVdhbyqnFk8ODyFfUnaq3fkC3hR3f0kmYgI41sljnXXjqwMoW9wRzBMINk+3k6P8cbxfmJD4/Paj8FwmHDsRfuoI6Jj8M76H3CTsZCZKDJjM3BQQ6Kb2m+bQ0LMjfVDyxoRgvfF//M/EEkPrKWyU2C3YBXpxaBquO4C8A0ujVmGEEqsxN1HX9lu6c5OMm8huDxwWFd7OHMs3avGpr7RP7DUnTikXrh6X0";
            // var base64Cert = "<?= $token ?>";
            // // var serverCertificate = base64DecodeUint8Array(base64Cert);

            // player.configure({
            //     drm:{
            //         servers:{
            //             'com.widevine.alpha': 'https://license.pallycon.com/ri/licenseManager.do',
            //             'com.microsoft.playready': 'https://license.pallycon.com/ri/licenseManager.do'
            //         },
            //         advanced: {
            //             'com.widevine.alpha': {
            //                 // serverCertificate: serverCertificate
            //                 'videoRobustness': 'SW_SECURE_CRYPTO',
            //                 'audioRobustness': 'SW_SECURE_CRYPTO'
            //             }
            //         }
            //     }
            // });

            // player.getNetworkingEngine().registerRequestFilter(function(type, request) {
            //     if (type == shaka.net.NetworkingEngine.RequestType.LICENSE) {
            //         if (is_chrome_or_firefox) {
            //             request.headers['pallycon-customdata-v2'] = base64Cert;
            //         } else {
            //             // request.headers['pallycon-customdata-v2'] = 'WM92HmV/aEtHgkIeKbAnZbRl52BofvWtsPYVWbYMbOpAYSb+yJzTF97QBF1Szbq/rG9eQ6la+5nPV9vGXI6ZUGrM6hhfyyJInOB3tOCoFJhFkgn55rSC47Nbgno32fN8MKT1EV1daXzER1qV1EAE50SfXWlib29kVo+futrK/JwwzOw7Ujx4N+UmUf0TLROM';
            //         }
            //     }
            // });
        </script>
    </body>
</html>