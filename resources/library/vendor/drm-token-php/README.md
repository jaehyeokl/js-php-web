# PallyCon PHP Token Sample 

## Requirements

- PHP Version 5.6 or later
- Install autoloader using Composer

## Quick Example

Refer to tests/SampleTest.php

```php
<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use PallyCon\Exception\PallyConTokenException;
use PallyCon\PallyConDrmTokenClient;
use PallyCon\TokenBuilder;
use PallyCon\PlaybackPolicyRequest;

$config = include "config/config.php";

try{
    // create tokenClient
    $pallyConTokenClient = new PallyConDrmTokenClient();
    
    /* create playback policy rule */
    // https://pallycon.com/docs/en/multidrm/license/license-token/#playback-policy
    
    //persistent : true / duration : 600
    $playbackPolicyRequest = new PlaybackPolicyRequest(true, 600);
    
    //SecurityPolicy: SecurityPolicyRequest.php
    //$securityPolicyRequest = new SecurityPolicyRequest("ALL");
    
    //ExternalKey: ExternalkeyRequest.php
    
    /* build playback policy */
    //https://pallycon.com/docs/en/multidrm/license/license-token/#token-rule-json
    $policyRequest = (new TokenBuilder)
        ->playbackPolicy($playbackPolicyRequest)
    //->securityPolicy($securityPolicyRequest)
        ->build();
    
    /* create token */
    // siteId, accessKey, siteKey, userId, cid, policy are mandatory
    // https://pallycon.com/docs/en/multidrm/license/license-token/#token-json-example
    $result = $pallyConTokenClient
        ->playReady()
        ->siteId($config["siteId"])
        ->accessKey($config["accessKey"])
        ->siteKey($config["siteKey"])
        ->userId("testUser")
        ->cid("testCID")
        ->policy($policyRequest)
        ->execute();    
    
}catch (PallyConTokenException $e){
    $result = $e->toString();
}
    echo $result;
?>


ExternalKeyRequest

```

