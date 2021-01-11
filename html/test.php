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
    </head>
    <body>
        <div>
            <!-- <video id="videoPlayer" controls></video> -->
            <!-- <video>
                <source src="/video/streaming/my_video_manifest.mpd">
                <source src="/video/streaming/audio.webm">
            </video> -->
            <video data-dashjs-player autoplay controls src="/video/streaming/my_video_manifest.mpd">
                <source src="/video/streaming/audio.webm">
            </video>
            <script src="http://cdn.dashjs.org/latest/dash.all.min.js"></script>
        </div>
        <!-- <script src="http://cdn.dashjs.org/latest/dash.all.min.js"></script>
        <script>
            (function(){
                var url = "/video/project/test1.mpd";
                var player = dashjs.MediaPlayer().create();
                player.initialize(document.querySelector("#videoPlayer"), url, true);
            })();
        </script> -->
    </body>
</html>