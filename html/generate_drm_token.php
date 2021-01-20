<?php
    // 유저가 jaehyeok.ml/live.php 에서 입력한 값을 전달받고 이를 통해 라이센스 토큰을 생성하여 반환한다
    // 라이센스 토큰을 통하여 인증 과정을 거친 후, CDM 라이센스 서버에 있는 DRM 복호화 키를 받을 수 있다 

    include_once("../resources/drm_config.php"); // PallyCon DRM key
    define("IV","0123456789abcdef"); // DRM policy(정책)을 암호화할때 사용되는 상수

    // 라이센스 토큰을 만드는데 필요한 데이터를 전달받는다 (drmType, userId, contentid)
    $getData = json_decode(file_get_contents('php://input'), true);
    $drmType = $getData['drmType'];
    $userId = $getData['userId']; // "LICENSETOKEN" default 값
    // $contentId = $getData['contentId'];
    $contentId = 'test4';
    $timestamp = gmdate("Y-m-d\Th:i:s\Z"); // GMT 시간

    // DRM 정책 설정 (해당 내용은 스트리밍 기본값)
    // 설정된 정책에 맞게 DRM 보안이 적용되며, 토큰을 만드는데 사용된다
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
    
    //$policy 와 같음,
    // $samplePolicy2 = array (
    //     'policy_version' => 2,
    // );

    // 예제의 정책
    // $samplePolicy = array (
    //     'playback_policy' => 
    //     array (
    //       'limit' => true,
    //       'persistent' => false,
    //       'duration' => 3600,
    //     ),
    // );
    
    
    // 정책을 AES256 으로 암호화
    $policyString = openssl_encrypt(json_encode($policy), "AES-256-CBC", $siteKey, 0, IV);

    // 사이트, 유저, 컨텐츠 정보를 이은 문장을 통해 Hash 를 생성
    $body = $accessKey.$drmType.$siteId.$userId.$contentId.$policyString.$timestamp;
    $hashString = base64_encode(hash("sha256", $body, true));

    // 라이센스 요청을 위한 토큰 생성
    // 앞에서 암호화한 정책과, 해쉬를 포함한 json 을 통해 생성한다
    $tokenJson = array (
        'drm_type' => $drmType,
        'site_id' => $siteId,
        'user_id' => $userId,
        'cid' => $contentId,
        'token' => $policyString,
        'timestamp' => $timestamp,
        'hash' => $hashString,
        'response_format' => 'original', // default
        'key_rotation' => false // default
    );

    $licenseToken = base64_encode(json_encode($tokenJson));
    
    // 라이센스 토큰 리턴
    echo json_encode([ 'licenseToken' => $licenseToken], JSON_THROW_ON_ERROR, 512);
?>