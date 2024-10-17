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
    <link rel="stylesheet" href="/modules/news/mainstyle.css?v=1.0.2">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    // player.........................
    <meta charset="utf-8" />
    <meta name="description" property="og:description"
        content="A simple HTML5 media player with custom controls and WebVTT captions." />
    <meta name="author" content="Sam Potts" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Icons -->
    <link rel="icon" href="https://cdn.plyr.io/static/icons/favicon.ico" />
    <link rel="icon" type="image/png" href="https://cdn.plyr.io/static/icons/32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="https://cdn.plyr.io/static/icons/16x16.png" sizes="16x16" />
    <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.plyr.io/static/icons/180x180.png" />

    <!-- Open Graph -->
    <meta property="og:title" content="Plyr - A simple, customizable HTML5 Video, Audio, YouTube and Vimeo player" />
    <meta property="og:site_name" content="Plyr" />
    <meta property="og:url" content="https://plyr.io" />
    <meta property="og:image" content="https://cdn.plyr.io/static/icons/1200x630.png" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@sam_potts" />
    <meta name="twitter:creator" content="@sam_potts" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- Docs styles -->
    <link rel="stylesheet" href="dist/demo.css" />

    <!-- Preload -->
    <link rel="preload" as="font" crossorigin type="font/woff2"
        href="https://cdn.plyr.io/static/fonts/gordita-medium.woff2" />
    <link rel="preload" as="font" crossorigin type="font/woff2"
        href="https://cdn.plyr.io/static/fonts/gordita-bold.woff2" />

    <!-- Google Analytics-->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132699580-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-132699580-1');
    </script>
</head>

<body>

    <div class="main-wrapper">
        <!-- <div id="embedVideo"  class="mainVideo"></div> -->
        <video class="mainVideo" src="" id="videos" autoplay muted crossorigin playsinline
            data-poster="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg" id="player" size="576">
        </video>
        <div class="footer">
            <div class="logo-wrap">
                <img src="https://kanact-media.fra1.cdn.digitaloceanspaces.com/{{ $channel->logo }}"
                    class="logo" alt="">
                <!-- <span id="time"></span> -->
                <span id="internetSpeed"></span>
            </div>
            <!-- <div class="bn-breaking-news" id="newsTicker10">
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
            </div>  -->
            <div class="ticker-wrap" id="ticker">
                <!-- <div class="breakingTicker  animatedBreaking" id="breakingVideo">
                    <img src="/modules/news/dinNews/images/breakingNewsTicker-new.gif" alt="">
                </div> -->
                <img class="breakingTicker" id="breakingVideo"
                    src="/modules/news/dinNews/images/breakingNewsTicker-new.gif" alt="">
                <img class="breakingTicker" id="updateVideo" src="/modules/news/dinNews/images/News-Update-Ticker.gif"
                    alt="">

                <div class="onlyTicker animatedBreaking">

                    <!-- <img src="/modules/news/dinNews/images/Main-Breaking-News-Animation.gif"
                        class="ticker tick tickerBG ticker-red breakingTick" id="main-braking-animation" alt=""> -->
                    <!-- <img src="/modules/news/dinNews/images/Main-Ticker.gif"
                        class="ticker tick tickerBG ticker-red breakingTick" alt=""> -->
                    <img src="/modules/news/dinNews/images/Main-Breaking-News-Animation.gif"
                        class="ticker tick tickerBG ticker-red breakingTick" id="main-braking-animation" alt="">

                    <img src="/modules/news/dinNews/images/Main-Ticker.gif"
                        class=" ticker tick tickerBG ticker-blue breakingTick" id="update-news-animation" alt="">
                    <div class="ticker-wraps bn-breaking-news" id="newsTicker10">
                        <div class=" ticker tick  bn-news">
                            <ul class="" id="dynamicTicker">
                            </ul>
                        </div>
                        <div class="ticker tick " id="dynamicTickerRed">
                        </div>
                    </div>
                    <div class="ticker-wraps bn-breaking-news" id="newsTicker11">
                        <div class=" ticker tick  bn-news">
                            <ul class="" id="breakingTickerRed">
                            </ul>
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
            {{-- <div class="breakingTickerScroll">
                <p class="ticker-text" id="breakingTickerRed">
                </p>
            </div> --}}
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
        let latest_ticker = '';
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

        function getTickers() {
            $.ajax({
                url: "/news/channel-ticker/" + "{{ $channel->id }}",
                type: 'GET',
                success: function(response) {
                    let tickerStartTime = 0;
                    let tickerCount = 0
                    var tickerSource = [];

                    var evaluateTicker = pusher.subscribe("evaluate-ticker");
                    evaluateTicker.bind("evaluate", function(data) {
                        if (data.status == 'approve') {
                            latest_ticker = JSON.parse(data.ticker);
                        }
                    })

                    setInterval(function() {
                        document.getElementById('breakingTickerRed').style.display = 'block';
                        document.getElementById('dynamicTicker').style.display = 'none';

                    }, 10000);

                    jQuery.each(response, function(index, item) {
                        // tickerSource[index] = item;
                        document.getElementById('breakingTickerRed').innerHTML +=
                            `<li><a href='#'>${item.ticker}</a> </li>`;
                        $('#newsTicker11').breakingNews({
                            effect: 'slide-down',
                        });
                    });
                    let latestTickerHandler = () => {
                        clearInterval(triggerBreaking);
                    }

                    let videoLoop = () => {
                        if (tickerSource[tickerCount]) {
                            if (tickerSource[tickerCount].ticker != null) {
                                // document.getElementById('ticker').innerHTML +=
                                //         `<li><a href='#'>${tickerSource[tickerCount].ticker}</a> </li>`
                            } else {
                                // document.getElementById('ticker').innerHTML +=
                                //         `<li><a href='#'>${tickerSource[tickerCount].ticker}</a> </li>`
                            }
                        }
                        // channel_id_count++;
                        // document.getElementById('dynamicTicker').innerHTML = ''
                        clearInterval(trigger);
                        document.getElementById('dynamicTicker').innerHTML = ""
                        if (latest_video != "") {
                            latestTickerHandler();
                        } else {
                            if (videoCount != videoSource.length - 1) {
                                videoCount++;
                                videoLoop();
                            } else {
                                getLatestNews();
                            }
                        }
                    }
                }
            })
        }

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
                    var trigger = "";
                    news = response.news;
                    videoStartTime = response.videoStartTime
                    // console.log(document.getElementById('dynamicTickerBlue').innerHTML, 'ddddddd')
                    // document.getElementById('dynamicTickerRed').innerHTML = ''
                    // document.getElementById('dynamicTickerBlue').innerHTML = ''
                    document.getElementById('dynamicTicker').innerHTML = ""

                    jQuery.each(news, function(index, item) {
                        videoSource[index] = item;
                    });
                    jQuery.each(response.slots, function(index, item) {
                        slotSource[index] = item;
                    });

                    let videoUrl = document.getElementById("videos");
                    var channel = pusher.subscribe('evaluate-video');
                    channel.bind('evaluate', function(data) {
                        if (JSON.parse(data.evaluation).status == "approve") {
                            latest_video = JSON.parse(data.evaluation);
                            bool_latest_video = true
                        }
                        if (videoSource.length == 0) {
                            newsVideoHandler();
                        }
                    });
                    clearInterval(trigger);

                    var channel = pusher.subscribe('channel');
                    channel.bind('slot', function(data) {
                        latestSlot = JSON.parse(data.slot)
                    });

                    function slotVideoHandler() {
                        videoUrl.setAttribute("src",
                            `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${slotSource[slotCount].video_path}`
                        );

                    }

                    function latestSlotVideoHandler() {
                        videoUrl.setAttribute("src",
                            `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${latestSlot.video_path}`
                        );

                    }

                    var testConnectionSpeed = {
                        imageAddr: "https://upload.wikimedia.org/wikipedia/commons/a/a6/Brandenburger_Tor_abends.jpg", // this is just an example, you rather want an image hosted on your server
                        downloadSize: 2707459, // Must match the file above (from your server ideally)
                        run: function(mbps_max, cb_gt, cb_lt) {
                            testConnectionSpeed.mbps_max = parseFloat(mbps_max) ? parseFloat(mbps_max) :
                                0;
                            testConnectionSpeed.cb_gt = cb_gt;
                            testConnectionSpeed.cb_lt = cb_lt;
                            testConnectionSpeed.InitiateSpeedDetection();
                        },
                        InitiateSpeedDetection: function() {
                            window.setTimeout(testConnectionSpeed.MeasureConnectionSpeed, 1);
                        },
                        result: function() {
                            var duration = (endTime - startTime) / 1000;
                            var bitsLoaded = testConnectionSpeed.downloadSize * 8;
                            var speedBps = (bitsLoaded / duration).toFixed(2);
                            var speedKbps = (speedBps / 1024).toFixed(2);
                            var speedMbps = (speedKbps / 1024).toFixed(2);
                            if (speedMbps >= (testConnectionSpeed.max_mbps ? testConnectionSpeed
                                    .max_mbps : 1)) {
                                testConnectionSpeed.cb_gt ? testConnectionSpeed.cb_gt(speedMbps) :
                                    false;
                            } else {
                                testConnectionSpeed.cb_lt ? testConnectionSpeed.cb_lt(speedMbps) :
                                    false;
                            }
                        },
                        MeasureConnectionSpeed: function() {
                            var download = new Image();
                            download.onload = function() {
                                endTime = (new Date()).getTime();
                                testConnectionSpeed.result();
                            }
                            startTime = (new Date()).getTime();
                            var cacheBuster = "?nnn=" + startTime;
                            download.src = testConnectionSpeed.imageAddr + cacheBuster;
                        }
                    }

                    navigator.onLine ?
                        testConnectionSpeed.run(1.5, function(mbps) {
                                document.getElementById('internetSpeed').innerHTML = mbps + " Kbps"

                            },
                            function(mbps) {
                                document.getElementById('internetSpeed').innerHTML = mbps + " Mbps"
                            }) :
                        document.getElementById('internetSpeed').innerHTML = "Offline"

                    function displayClock() {
                        var time = new Date()
                        //........latest video......
                        if (time.getSeconds() % 5 == 0) {
                            navigator.onLine ?
                                testConnectionSpeed.run(1.5, function(mbps) {
                                        document.getElementById('internetSpeed').innerHTML = mbps + " Kbps"

                                    },
                                    function(mbps) {
                                        document.getElementById('internetSpeed').innerHTML = mbps + " Mbps"
                                    }) :
                                document.getElementById('internetSpeed').innerHTML = "Offline"
                            // testConnectionSpeed.run(1.5, function(mbps){
                            //     document.getElementById('internetSpeed').innerHTML = mbps + " Mbps"

                            // },
                            //  function(mbps){
                            //      document.getElementById('internetSpeed').innerHTML = mbps + " Mbps"
                            // } )
                        }
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

                                videoUrl.onended = function() {
                                    latestSlotVideoHandler();
                                }
                            } else if (time.toLocaleTimeString('en-US', {
                                    hour12: false
                                }) == latestSlot.end_time) {
                                slotCount++;
                                if (videoCount != videoSource.length - 1 && videoSource[videoCount] !=
                                    undefined) {
                                    // videoCount++;
                                    videoLoop();
                                } else {
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
                                    getLatestNews();
                                }
                            }
                        }
                        setTimeout(displayClock, 1000);
                    }

                    let newsVideoHandler = () => {
                        videoUrl.setAttribute("src",
                            `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${latest_video.video_path}`
                        );
                        document.getElementById('dynamicTicker').innerHTML = ""
                        if (videoSource[videoCount].ticker_text != null) {
                            if (videoSource[videoCount].video_type_id != 1) {

                                document.getElementById('main-braking-animation').classList.remove(
                                    'breaking-ticker')
                                document.getElementById('update-news-animation').classList.remove(
                                    'ticker-blue')
                                document.getElementById('update-news-animation').classList.add(
                                    'update-ticker')
                                document.getElementById('main-braking-animation').classList.add(
                                    'ticker-red')


                                JSON.parse(videoSource[videoCount].ticker_text).map((data, index) => {
                                    document.getElementById('dynamicTicker').innerHTML +=
                                        `<li><a href='#'>${data}</a> </li>`
                                })

                                $('#newsTicker10').breakingNews({
                                    effect: 'slide-down',
                                });
                                trigger = setInterval(function() {
                                    document.getElementById('updateVideo').classList.add(
                                        "animatedTickerupdate");
                                    setTimeout(function() {
                                        document.getElementById('updateVideo').classList
                                            .remove("animatedTickerupdate");
                                    }, 4000)

                                }, 14000);
                            } else {

                                document.getElementById('update-news-animation').classList.remove(
                                    'update-ticker')
                                document.getElementById('main-braking-animation').classList.remove(
                                    'ticker-red')
                                document.getElementById('main-braking-animation').classList.add(
                                    'breaking-ticker')
                                document.getElementById('update-news-animation').classList.add(
                                    'ticker-blue')


                                JSON.parse(videoSource[videoCount].ticker_text).map((data, index) => {
                                    document.getElementById('dynamicTicker').innerHTML +=
                                        `<li><a href='#'>${data}</a> </li>`
                                })

                                $('#newsTicker10').breakingNews({
                                    effect: 'slide-down',
                                });
                                trigger = setInterval(function() {
                                    document.getElementById('breakingVideo').classList.add(
                                        "animatedTickerbreaking");
                                    setTimeout(function() {
                                        document.getElementById('breakingVideo').classList
                                            .remove("animatedTickerbreaking");
                                    }, 4000)
                                }, 14000);

                            }
                        }


                        videoUrl.onended = function() {
                            bool_latest_video = false;
                            latest_video = "";
                            clearInterval(trigger);

                            if (videoCount != videoSource.length - 1 && videoSource.length != 0) {
                                videoLoop();
                            } else {
                                getLatestNews();
                            }
                        }
                    }

                    let onLoadFunction = () => {
                        displayClock();
                        document.getElementById('dynamicTicker').innerHTML = ""

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
                                if (videoSource[videoCount].video_type_id != 1) {
                                    document.getElementById('main-braking-animation').classList.remove(
                                        'breaking-ticker')
                                    document.getElementById('update-news-animation').classList.remove(
                                        'ticker-blue')
                                    document.getElementById('update-news-animation').classList.add(
                                        'update-ticker')
                                    document.getElementById('main-braking-animation').classList.add(
                                        'ticker-red')

                                    JSON.parse(videoSource[videoCount].ticker_text).map((data, index) => {
                                        document.getElementById('dynamicTicker').innerHTML +=
                                            `<li><a href='#'>${data}</a> </li>`
                                    })

                                    $('#newsTicker10').breakingNews({
                                        effect: 'slide-down',
                                    });

                                    trigger = setInterval(function() {
                                        document.getElementById('updateVideo').classList.add(
                                            "animatedTickerupdate");
                                        setTimeout(function() {
                                            document.getElementById('updateVideo').classList
                                                .remove("animatedTickerupdate");
                                        }, 4000)

                                    }, 14000);
                                } else {
                                    document.getElementById('update-news-animation').classList.remove(
                                        'update-ticker')
                                    document.getElementById('main-braking-animation').classList.remove(
                                        'ticker-red')
                                    document.getElementById('main-braking-animation').classList.add(
                                        'breaking-ticker')
                                    document.getElementById('update-news-animation').classList.add(
                                        'ticker-blue')

                                    JSON.parse(videoSource[videoCount].ticker_text).map((data, index) => {
                                        document.getElementById('dynamicTicker').innerHTML +=
                                            `<li><a href='#'>${data}</a> </li>`
                                    })

                                    $('#newsTicker10').breakingNews({
                                        effect: 'slide-down',
                                    });

                                    trigger = setInterval(function() {
                                        document.getElementById('breakingVideo').classList.add(
                                            "animatedTickerbreaking");
                                        setTimeout(function() {

                                            document.getElementById('breakingVideo')
                                                .classList.remove("animatedTickerbreaking");
                                        }, 4000)
                                    }, 14000);
                                }
                            }
                        }
                        videoUrl.onended = function() {
                            boolLoad = false;
                            videoCount++;
                            clearInterval(trigger);
                            if (videoSource.length > 1) {
                                videoLoop();
                            } else {
                                getLatestNews();
                            }
                        };
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
                        document.getElementById('dynamicTicker').innerHTML = ""

                        if (videoSource[videoCount].ticker_text != null) {
                            if (videoSource[videoCount].video_type_id != 1) {

                                document.getElementById('main-braking-animation').classList.remove(
                                    'breaking-ticker')
                                document.getElementById('update-news-animation').classList.remove(
                                    'ticker-blue')
                                document.getElementById('update-news-animation').classList.add(
                                    'update-ticker')
                                document.getElementById('main-braking-animation').classList.add(
                                    'ticker-red')

                                JSON.parse(videoSource[videoCount].ticker_text).map((data, index) => {
                                    document.getElementById('dynamicTicker').innerHTML +=
                                        `<li><a href='#'>${data}</a> </li>`
                                })

                                $('#newsTicker10').breakingNews({
                                    effect: 'slide-down',
                                });
                                document.getElementById('breakingVideo').classList.remove(
                                    "animatedTickerbreaking")

                                trigger = setInterval(function() {
                                    document.getElementById('updateVideo').classList.add(
                                        "animatedTickerupdate");
                                    setTimeout(function() {
                                        document.getElementById('updateVideo').classList
                                            .remove("animatedTickerupdate");
                                    }, 4000)

                                }, 14000);
                            } else {
                                document.getElementById('update-news-animation').classList.remove(
                                    'update-ticker')
                                document.getElementById('main-braking-animation').classList.remove(
                                    'ticker-red')
                                document.getElementById('main-braking-animation').classList.add(
                                    'breaking-ticker')
                                document.getElementById('update-news-animation').classList.add(
                                    'ticker-blue')

                                JSON.parse(videoSource[videoCount].ticker_text).map((data, index) => {
                                    document.getElementById('dynamicTicker').innerHTML +=
                                        `<li><a href='#'>${data}</a> </li>`
                                })

                                $('#newsTicker10').breakingNews({
                                    effect: 'slide-down',
                                });
                                document.getElementById('updateVideo').classList.remove(
                                    "animatedTickerupdate")

                                trigger = setInterval(function() {
                                    document.getElementById('breakingVideo').classList.add(
                                        "animatedTickerbreaking");
                                    setTimeout(function() {
                                        document.getElementById('breakingVideo').classList
                                            .remove("animatedTickerbreaking");
                                    }, 4000)

                                }, 14000);
                            }
                        }
                        videoUrl.onended = function() {
                            // channel_id_count++;
                            // document.getElementById('dynamicTicker').innerHTML = ''
                            clearInterval(trigger);
                            document.getElementById('dynamicTicker').innerHTML = ""
                            if (latest_video != "") {
                                newsVideoHandler();
                            } else {
                                if (videoCount != videoSource.length - 1) {
                                    videoCount++;
                                    videoLoop();
                                } else {
                                    getLatestNews();
                                }
                            }
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
            getTickers();
        });
    </script>

    <script src="/modules/news/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // $('#newsTicker10').breakingNews({
            //     effect: 'slide-down',
            //     });
        });
    </script>
</body>

</html>
