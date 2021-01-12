<?php
    
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
            const manifestUrl = "/video/streaming/talkpic_test.mpd"

            function initApp(){
                shaka.polyfill.installAll();

                if(shaka.Player.isBrowserSupported()){
                    initPlayer();
                } else{
                    console.error("Browser not supported!");
                }
            }

            function initPlayer(){
                const video = document.getElementById('video');
                const player = new shaka.Player(video);

                window.player = player;

                player.addEventListener('error', onErrorEvent);

                player.load(manifestUrl).then(function(){
                    console.log("The video has now been loaded!");
                }).catch(onError);
            }

            function onErrorEvent(event){
                onError(event.detail);
            }

            function onError(error){
                console.error("Error code", error.code, 'object', error);
            }

            document.addEventListener("DOMContentLoaded", initApp);
        </script>
    </body>
</html>