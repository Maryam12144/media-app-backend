<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kannact Media </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500&display=swap" rel="stylesheet">
    {{-- <link rel="stylesheet" href="/modules/news/dinNews/style.css"> --}}
    <link rel="stylesheet" href="/modules/news/mainstyle.css?v=1.0.0">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>

<body>

    <div class="main-wrapper">
        <!-- <div id="embedVideo"  class="mainVideo"></div> -->
        <video class="mainVideo" src="" id="videos" autoplay muted>
        </video>
        <div class="footer">
            <div class="logo-wrap">
                <img src="https://kanact-media.fra1.cdn.digitaloceanspaces.com/{{ $channel->logo }}"
                    class="logo" alt="">
                <span id="time"></span>
            </div>
            {{-- <div class="bn-breaking-news" id="newsTicker10">
                <div class="bn-news">
                    <ul class="">
                        <li><a href="#"> حمزہ شہباز کو ووٹ دیا تھا۔ تواس وقت سوال یہ ہے کہ اس فیصلے کے بعد حمزہ شہباز کی
                                حکومت
                                کہاں کھڑی ہے؟ حمزہ شہباز</a></li>
                        <li><a href="#">شکر ہے سپریم کورٹ نے ووٹ بیچنے والوں کے ووٹ رد کر دیے: عمران خان</a></li>
                        <li><a href="#">ملک کے اندر اور باہر مجھے قتل کرنے کی سازش ہو رہی ہے، عمران خان</a></li>
                        <li><a href="#">افسوس، سازش روکنے والوں نے کچھ نہیں کیا: عمران خان</a></li>
                    </ul>
                </div>
            </div> --}}
            <div class="ticker-wrap" id="ticker">
                <div class="breakingTicker  animatedBreaking" id="breakingVideo">
                    <img src="/modules/news/dinNews/images/breakingNewsTicker-new.gif" alt="">
                </div>
                <div class="onlyTicker animatedBreaking">
                    <img src="/modules/news/dinNews/images/Main-Ticker.gif"
                        class="ticker tick tickerBG ticker-red breakingTick" alt="">
                    <img src="/modules/news/dinNews/images/Main-Ticker.gif"
                        class=" ticker tick tickerBG ticker-blue breakingTick" alt="">
                    <div class="ticker-wraps bn-breaking-news" id="newsTicker10">
                        <div class=" ticker tick tickerBG ticker-blue bn-news">
                            <ul class="">

                                <li><a href="#" id="dynamicTicker"></a></li>

                            </ul>
                        </div>
                        <div class="ticker tick tickerBG ticker-red" id="dynamicTickerRed">
                        </div>
                    </div>
                </div>
            </div>
            <div class="controls-wrap">
                <button onclick="volumeHandler()" class="volumeBtn" id="volume_toggle"><i class="fa fa-volume-off"
                        aria-hidden="true"></i></button>
            </div>
            <div class="followUs" id="footer">
                <img src="/modules/news/dinNews/images/Follow-Up-Ticker.png" />
            </div>
            <div class="followMarquee">Kanact is changing the face of media with 100 Channels reaching out to 160
                democratic countries with their local content in 10 languages, providing unbiased news, views,
                entertainment and information content. Kanact media is a platform designed to operate without any
                discrimination to nationalities, religions, ethnicities and business interests.</div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/modules/news/script.js"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        let latest_video = '';
        let bool_latest_video = false;
        Pusher.logToConsole = true;
        var pusher = new Pusher('d3d0a052dfa30840b4b4', {
            cluster: 'mt1'
        });

        var news = [];
        let boolLoad = false;
        let mute = true;
        let channel_id_count = 1;
        let per_day_hour_count = false;
        let latestSlot = {};
        let boolSlot = false;

        function getLatestNews() {
            var url = window.location.pathname;
            var id = url.substring(url.lastIndexOf('/') + 1);
            $.ajax({
                url: "/news/channel-ajax/" + "{{ $channel->id }}",
                type: 'GET',
                success: function(response) {
                    let videoStartTime = 0;
                    let videoCount = 0
                    let slotCount = 0
                    var slotSource = [];
                    var videoSource = [];
                    news = response.news;
                    videoStartTime = response.videoStartTime
                    // console.log(document.getElementById('dynamicTickerBlue').innerHTML, 'ddddddd')
                    // document.getElementById('dynamicTickerRed').innerHTML = ''
                    // document.getElementById('dynamicTickerBlue').innerHTML = ''
                    jQuery.each(news, function(index, item) {
                        videoSource[index] = item;
                    });
                    jQuery.each(response.slots, function(index, item) {
                        slotSource[index] = item;
                    });

                    let videoUrl = document.getElementById("videos");
                    var channel = pusher.subscribe('modify-video');
                    channel.bind('modify', function(data) {
                        if (JSON.parse(data.evaluation).status == "approve") {
                            latest_video = JSON.parse(data.evaluation).video_path;
                            bool_latest_video = true
                            console.log(JSON.parse(data.evaluation), 'pusherrr');
                        }
                    });
                    let newsVideoHandler = () => {
                        videoUrl.setAttribute("src",
                            `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${latest_video}`
                        );
                        videoUrl.onended = function() {
                            bool_latest_video = false;
                            latest_video = ""
                            if (videoCount != videoSource.length - 1) {
                                // console.log('dfdfdfdddd')
                                // videoCount++;
                                videoLoop();
                            } else {
                                getLatestNews();
                            }
                        }
                    }
                    let videoLoop = () => {

                        videoUrl.setAttribute("src",
                            `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}`
                        );
                        $.ajax({
                            url: "/api/news/count-views/" + videoSource[videoCount].id,
                            type: 'GET',
                            success: function(response) {}
                        })
                        if (videoSource[videoCount].ticker_text != null) {
                            if (videoSource[videoCount].video_type_id != 1) {
                                var ticker = document.getElementById('dynamicTickerRed').innerHTML +=
                                    '<div class="ticker__item"><span class="block"></span>"' +
                                    videoSource[videoCount].ticker_text.replace(/["']/g, "") + '"</div>'
                            } else {
                                console.log(videoSource[videoCount].ticker_text, 'trtprptprptprtprt')
                                JSON.parse(videoSource[videoCount].ticker_text).map((data, index) => {
                                    console.log(data, 'ticer')
                                    document.getElementById('dynamicTicker').innerHTML = data
                                        .replace(/["']/g, "")
                                })
                                // var ticker = document.getElementById('dynamicTickerBlue').innerHTML +=
                                //     '<div class="ticker__item"><span class="block"></span>"' +
                                //     videoSource[videoCount].ticker_text.replace(/["']/g, "") + '"</div>'

                            }
                        }
                        videoUrl.onended = function() {
                            // channel_id_count++;
                            if (latest_video != "") {
                                newsVideoHandler();
                            } else {
                                if (videoCount != videoSource.length - 1) {
                                    console.log('dfdfdfdddd')
                                    videoCount++;

                                    videoLoop();
                                } else {
                                    getLatestNews();
                                }
                            }
                        };
                    }
                    // ZrHbKl9Dogmo1nXvCLxD8aJwizxJvylGIqTAiYOi

                    // poJX0Pn9IQaAXCKagkY0pDxIWF6gB5emhEjVQyd0
                    // function breakingNewsFunction() {
                    //     videoUrl.setAttribute("src",
                    //         `https://kanact-media.fra1.cdn.digitaloceanspaces.com/uploads/4jnCpEaMmKZleAE0wTFbLuiNdsIKeKTAgkHmNSSf.mp4`
                    //     );
                    //     videoUrl.onended = function() {
                    //         per_day_hour_count = false;
                    //         if (boolLoad == true) {
                    //             onLoadFunction()
                    //         } else {
                    //             videoLoop();
                    //         }
                    //     }
                    // }

                    var channel = pusher.subscribe('channel');
                    channel.bind('slot', function(data) {
                        latestSlot = JSON.parse(data.slot)
                        console.log(latestSlot, 'ghhhhh');
                    });

                    function slotVideoHandler() {
                        console.log(slotSource[slotCount], 'iferror', slotSource[slotCount])
                        videoUrl.setAttribute("src",
                            `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${slotSource[slotCount].video_path}`
                        );

                    }

                    function latestSlotVideoHandler() {
                        console.log(latestSlot, 'iferror')
                        videoUrl.setAttribute("src",
                            `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${latestSlot.video_path}`
                        );

                    }

                    function displayClock() {
                        var time = new Date()
                        //........latest video......
                        if (Object.keys(latestSlot).length > 0 && latestSlot != undefined) {
                            if (time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) == latestSlot.start_time) {
                                latestSlotVideoHandler()
                            } else if (time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) > latestSlot.start_time &&
                                time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) < latestSlot.end_time) {
                                console.log(time.toLocaleTimeString('en-US', {
                                        hour12: false
                                    }),
                                    'iam call again', latestSlot.end_time, 'startttime', slotCount)
                                videoUrl.onended = function() {
                                    latestSlotVideoHandler();
                                }
                                // displayClock();
                            } else if (time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) == latestSlot.end_time) {
                                slotCount++;
                                if (videoCount != videoSource.length - 1 && videoSource[videoCount] !=
                                    undefined) {
                                    // videoCount++;
                                    videoLoop();
                                } else {
                                    console.log('latest');

                                    getLatestNews();
                                }
                            }
                        }



                        if (slotSource.length > 0 && slotSource[slotCount] != undefined) {
                            if (time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) == slotSource[slotCount].start_time) {
                                slotVideoHandler()
                            } else if (time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) > slotSource[slotCount].start_time &&
                                time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) < slotSource[slotCount].end_time) {
                                console.log(time.toLocaleTimeString('en-US', {
                                        hour12: false
                                    }),
                                    'iam call again', slotSource[slotCount].end_time, 'startttime',
                                    slotCount)
                                videoUrl.onended = function() {
                                    slotVideoHandler();
                                }
                                // displayClock();
                            } else if (time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) == slotSource[slotCount].end_time) {
                                slotCount++;
                                if (videoCount != videoSource.length - 1 && videoSource[videoCount] !=
                                    undefined) {
                                    // videoCount++;
                                    videoLoop();
                                } else {
                                    console.log('latest');

                                    getLatestNews();
                                }
                            }
                        }
                        setTimeout(displayClock, 1000);
                    }
                    // poJX0Pn9IQaAXCKagkY0pDxIWF6gB5emhEjVQyd0
                    let onLoadFunction = () => {
                        displayClock();
                        // document.getElementById("embedVideo").innerHTML =  '<div style="padding:56.25% 0 0 0;position:relative;"><video src="'+`https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}`+'" autoplay style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="videos"></video></div>'
                        if (videoSource.length > 0 && per_day_hour_count != true) {
                            videoUrl.setAttribute("src",
                                `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}?autoplay=1&muted=1&#t=${videoStartTime}`
                            );
                            $.ajax({
                                url: "/api/news/count-views/" + videoSource[videoCount].id,
                                type: 'GET',
                                success: function(response) {}
                            })
                            if (videoSource[videoCount].ticker_text != null) {
                                // if(videoSource[videoCount].video_type_id != 1){
                                //     var ticker = document.getElementById('dynamicTickerRed').innerHTML += '<div class="ticker__item"><span class="block"></span>"'+ videoSource[videoCount].ticker_text.replace(/["']/g, "")+'"</div>'
                                // }
                                // else{

                                console.log(JSON.parse(videoSource[videoCount].ticker_text),
                                    'trtprptprptprtprt')
                                JSON.parse(videoSource[videoCount].ticker_text).map((data, index) => {
                                    console.log(data, 'ticer')
                                    document.getElementById('dynamicTicker').innerHTML = data
                                        .replace(/["']/g, "")
                                })


                                // var ticker = document.getElementById('dynamicTickerBlue').innerHTML +=
                                //     '<div class="ticker__item"><span class="block"></span>"' + videoSource[
                                //         videoCount].ticker_text.replace(/["']/g, "") + '"</div>'


                                // }
                            }
                        }
                        videoUrl.onended = function() {
                            boolLoad = false;
                            videoCount++;
                            // channel_id_count++;
                            if (videoSource.length > 1) {
                                videoLoop();
                            } else {
                                getLatestNews();
                            }
                            console.log('1timee', videoCount);
                        };
                    }
                    if (boolLoad == true) {
                        onLoadFunction()
                    } else {
                        videoLoop();
                    }
                    $(document).ready(function() {
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
            let volumeToggle = document.getElementById("volume_toggle");
            if (videoUrl != null) {
                if (videoUrl.muted == true) {
                    volumeToggle.innerHTML = '<i class="fa fa-volume-up" aria-hidden="true"></i>'
                    videoUrl.muted = false
                } else if (videoUrl.muted == false) {
                    volumeToggle.innerHTML = '<i class="fa fa-volume-off" aria-hidden="true"></i>'
                    videoUrl.muted = true
                }
            } else {
                return
            }
        }
        $(document).ready(function() {
            boolLoad = true;
            getLatestNews();
        });
    </script>

    <script src="/modules/news/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#newsTicker10').breakingNews({
                effect: 'slide-down',
                // themeColor: '#2eb872',
                // height: 50,
                // fontSize: '18px'
            });
        });
    </script>
</body>

</html>
