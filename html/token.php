<?php
    // 생략
    // $this->checkValidation();
    // #1 정책 생성
    // $this->_encPolicy = $this->createPolicy();
    // 해쉬 생성
    // $this->_hash = $this->createHash();
    // 모두 합쳐 결과가 만들어진다
    // $result = base64_encode(json_encode(["drm_type"=> $this->_drmType
    //     , "site_id"=> $this->_siteId
    //     , "user_id"=> $this->_userId
    //     , "cid"=> $this->_cid
    //     , "policy"=> $this->_encPolicy
    //     , "timestamp"=> $this->_timestamp
    //     , "response_format"=> $this->_responseFormat
    //     , "hash"=> $this->_hash]));
    // return $result;

    $siteId = "";
    $siteKey = "";
    $accessKey = "";

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
    
    $policyDetail = array (
        'policy_version' => 2,
        'playback_policy' => array (
            'persistent' => false, // 오프라인 라이센스 적용 X
            'license_duration' => 300,
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
                                        

    $policyString = openssl_encrypt(json_encode($policy), "AES-256-CBC", "kEssroEb7ztlOjMmIZjAGs5yiky1pK9B", false, IV);
    echo $policyString;


    // 해쉬생성
    $drmType = "Widevine";
    $userId = "LICENSETOKEN"; // 없을경우의 default 값
    $cid = "start";
    $timestamp = gmdate("Y-m-d\Th:i:s\Z");

    $body = $accessKey.$drmType.$siteId.$userId.$cid.$policy.$timestamp;
    $hash = base64_encode(hash("sha256", $body, true));

    echo "<br>";
    echo $hash;

    // 토큰생성
    $result = base64_encode(json_encode([$drmType, $siteId, $userId, $cid, $policy, gmdate("Y-m-d\Th:i:s\Z"), "original", $hash]));
    echo "<br>";
    echo $result;
?>

<!doctype html>
<html>
    <head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
        <title>token</title>
        <!-- <script src="http://cdn.dashjs.org/latest/dash.all.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/3.0.7/shaka-player.compiled.js"></script>  
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/aes.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/cipher-core.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/core.min.js"></script> -->

        <link rel="icon" href="data:;base64,iVBORw0KGgo=">
        <!-- <script src="js/test.js"></script> -->
    </head>
    <body>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script> -->
        <!-- <script src="path-to/bower_components/crypto-js/crypto-js.js"></script> -->
        <!-- <script>
            // #1
            const AES_IV = "0123456789abcdef";
            const siteInfo = {
                siteId: "JXIY",
                siteKey: "kEssroEb7ztlOjMmIZjAGs5yiky1pK9B",
                accessKey: "DktUxvh5MxoNUe4hIdtm6T9k16ANdre9"
            };

            let licenseInfo = {
                drmType: "Widevine",
                contentId: "bigbuckbunny",
                userId: "LICENSETOKEN" // 사용자 ID 없을 경우의 기본값
            };

            let licenseRule = {
                playback_policy: {
                    limit: true,
                    persistent: false,
                    duration: 3600
                }
            };

            console.log("license rule : " + JSON.stringify(licenseRule));

            // #2
            const crypto = require("crypto");
            
            var cipher = CryptoJS.createCipheriv("aes-256-cbc", siteInfo.siteKey, AES_IV);
            // var ciphertext = CryptoJS.AES.encrypt('my message', 'secret key 123').toString();
            // const cipher = CryptoJS.createCipheriv("aes-256-cbc", siteInfo.siteKey, AES_IV);

            // let encryptedRule = cipher.update(
            //     JSON.stringify(licenseRule),
            //     "utf-8",
            //     "base64"
            // );
            // encryptedRule += cipher.final("base64");

            // console.log("encrypted rule : " + encryptedRule);

        </script> -->
    </body>
</html>