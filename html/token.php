<?php
    define("IV","0123456789abcdef");

    $siteId = "TUTO";
    $siteKey = "lU5D8s3PWoLls3PWFWkClULlFWk5D8oC";
    $accessKey = "LT2FVJDp2Xr018zf4Di6lzvNOv3DKP20";

    // include_once("../resources/drm_config.php");




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
                                        

    $policyString = openssl_encrypt(json_encode($samplePolicy), "AES-256-CBC", $siteKey, false, IV);
    // $policyString = base64_encode($policyString);
    echo $policyString;
    

    // 해쉬생성
    $drmType = "Widevine";
    $userId = "test-user"; // 없을경우의 default 값
    $cid = "bigbuckbunny";
    $timestamp = gmdate("Y-m-d\Th:i:s\Z");

    // $body = $accessKey.$drmType.$siteId.$userId.$cid.$policy.$timestamp;
    // $hash = base64_encode(hash("sha256", $body, true));

    // echo "<br>";
    // echo $hash;

    // // 토큰생성
    // $result = base64_encode(json_encode([$drmType, $siteId, $userId, $cid, $policy, gmdate("Y-m-d\Th:i:s\Z"), "original", $hash]));
    // echo "<br>";
    // echo $result;
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