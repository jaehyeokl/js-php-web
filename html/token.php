<?php

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
        <script src="js/test.js"></script>
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