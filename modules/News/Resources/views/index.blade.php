<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kannact Media App</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="modules/news/style.css?v=1.0.1">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>
<div class="main-wrapper">
   <!-- <div id="embedVideo"  class="mainVideo"></div> -->
   <video class="mainVideo" src="" id="videos"  autoplay muted>
       </video>
   <div class="footer">
    <div class="logo-wrap">
        <img src="modules/news/images/main-logo.gif" class="logo" alt="">
        <span id="time"></span>
    </div>
        <div class="ticker-wrap" id="ticker">
        <div class="breakingTicker  animatedBreaking" id="breakingVideo">
                <img src="modules/news/images/breakingNewsTicker-new.gif" alt="">
            </div>
            <div class="onlyTicker animatedBreaking">
                <img src="modules/news/images/ticker-red-new.png" class="ticker tick tickerBG ticker-red breakingTick" alt="">
                <img src="modules/news/images/ticker-blue-new.png" class=" ticker tick tickerBG ticker-blue breakingTick" alt="">
                <div class="ticker-wraps">
                    <div class="ticker tick tickerBG ticker-blue" id="dynamicTickerBlue">
                    </div>
                    <div class="ticker tick tickerBG ticker-red" id="dynamicTickerRed">
                    </div>
                </div>
            </div>
        </div>
        <div class="controls-wrap">
            <button onclick="volumeHandler()" class="volumeBtn" id="volume_toggle"><i class="fa fa-volume-up" aria-hidden="true"></i></button>
        </div>
        <div class="followUs" id="footer" >
            <img src="modules/news/images/FollowUsTicker-new.png" />
        </div>
        <marquee behavior="scroll" class="followMarquee" scrollamount="10">Kanact is changing the face of media with 100 Channels reaching out to 160 democratic countries with their local content in 10 languages, providing unbiased news, views, entertainment and information content. Kanact media is a platform designed to operate without any discrimination to nationalities, religions, ethnicities and business interests.</marquee>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="modules/news/script.js"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher('1cdd3b8fbdc0c2780e75', {
        cluster: 'mt1'
        });
        var channel = pusher.subscribe('chat');
        channel.bind('video', function(data) {
            console.log(JSON.stringify(data));
        });
        var news=[];
        let boolLoad = false
        let mute = true
        function getLatestNews() {
         $.ajax ({
             url: "/news/ajaxindex",
             type:'GET',
             success: function (response) {
                 let videoStartTime = 0;
                 let videoCount = 0
                var videoSource = [];
                news = response.news;
                videoStartTime= response.videoStartTime
                document.getElementById('dynamicTickerRed').innerHTML = ''
                document.getElementById('dynamicTickerBlue').innerHTML = ''
                jQuery.each(news, function(index, item) {
                    videoSource[index] = item;
                });
                let videoUrl = document.getElementById("videos");
                let videoLoop = () => {
                    videoUrl.setAttribute("src",`https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}`);
                    if(videoSource[videoCount].ticker_text != null) {
                        if(videoSource[videoCount].video_type_id == 1){
                            var ticker = document.getElementById('dynamicTickerRed').innerHTML += '<div class="ticker__item"><span class="block"></span>"'+ videoSource[videoCount].ticker_text.replace(/["']/g, "")+'"</div>'
                        }
                        else{
                            var ticker = document.getElementById('dynamicTickerBlue').innerHTML += '<div class="ticker__item"><span class="block"></span>"'+ videoSource[videoCount].ticker_text.replace(/["']/g, "")+'"</div>'
                        }
                    }
                    videoUrl.onended = function() {
                        if(videoCount != videoSource.length-1){
                            console.log('2nd', videoCount);
                            videoCount++;
                            videoLoop();
                        }
                        else {
                            console.log(videoCount,'dfdfdf',videoSource.length)
                            getLatestNews();
                        }
                    };
                }
                let onLoadFunction = () => {
                    // document.getElementById("embedVideo").innerHTML =  '<div style="padding:56.25% 0 0 0;position:relative;"><video src="'+`https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}`+'" autoplay style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="videos"></video></div>'
                    videoUrl.setAttribute("src",`https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}`);
                    if(videoSource[videoCount].ticker_text != null) {
                        if(videoSource[videoCount].video_type_id == 1){
                            var ticker = document.getElementById('dynamicTickerRed').innerHTML += '<div class="ticker__item"><span class="block"></span>"'+ videoSource[videoCount].ticker_text.replace(/["']/g, "")+'"</div>'
                        }
                        else{
                            var ticker = document.getElementById('dynamicTickerBlue').innerHTML += '<div class="ticker__item"><span class="block"></span>"'+ videoSource[videoCount].ticker_text.replace(/["']/g, "")+'"</div>'
                        }
                    }
                    videoUrl.onended = function() {
                        boolLoad = false;
                        videoCount++;
                        if(videoSource.length > 1){
                            videoLoop();
                        }
                        else {
                            console.log(videoCount,'dfdfdf',videoSource.length)
                            getLatestNews();
                        }
                        console.log('1timee', videoCount);
                    };
                }
                if(boolLoad == true){
                    onLoadFunction()
                }
                else{
                    videoLoop();
                }
                $( document ).ready(function() {
                    console.log('load');
                });
                // document
                //     .getElementById("videos")
                //     .addEventListener("ended", getLatestNews, false);
            }
        })
        }
        const element = document.getElementById("videos");
        function playVid() {
        if (element.paused) element.play();
        else element.pause();
        }
        let volumeHandler = () => {
            let videoUrl = document.getElementById("videos");
            let  volumeToggle = document.getElementById("volume_toggle");
            if(videoUrl != null)
            {
                if(videoUrl.muted == true){
                    volumeToggle.innerHTML = '<i class="fa fa-volume-up" aria-hidden="true"></i>'
                    videoUrl.muted = false
                }
                else if(videoUrl.muted == false){
                    volumeToggle.innerHTML = '<i class="fa fa-volume-off" aria-hidden="true"></i>'
                    videoUrl.muted = true
                }
            }
            else{
                return
            }
        }
        $( document ).ready(function() {
            boolLoad = true;
            getLatestNews();
        });
    </script>
</body>
</html>