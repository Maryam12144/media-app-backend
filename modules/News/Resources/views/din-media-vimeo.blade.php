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
    <link rel="stylesheet" href="/modules/news/din-live-styles.css?v=1.0.1">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


</head>

<body>

    <div class="main-wrapper">
        <div id="embedVideo" class="mainVideo"></div>
        <div id="embedPreloadVideo" class="mainVideo"></div>

        <div class='embed-container'>
            {{-- <iframe src='' frameborder='0' id="videos" crossorigin playsinline id="videoPlaying"></iframe> --}}
        </div>

        {{-- <iframe class="mainVideo" preload src="" id="videos" muted crossorigin playsinline>
        </iframe> --}}

        <iframe class="mainVideo" preload src="" id="secondVideo" crossorigin playsinline>
        </iframe>
        <div class="footer">
            <div class="logo-wrap">
                <img src="/modules/news/dinLive/Din_Lahore.gif" class="logo" alt="">
                <span id="time"></span>
                {{-- <span id="internetSpeed"></span> --}}

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
                <div class="onlyTicker ">
                    <img src="/modules/news/dinNews/images/Main-Breaking-News-Animation.gif"
                        class="ticker tick tickerBG  breakingTick" alt="">
                    <!-- <img src="/modules/news/dinNews/images/Main-Ticker.gif"
                        class=" ticker tick tickerBG breakingTick" alt=""> -->
                    <div class="ticker-wraps bn-breaking-news" id="newsTicker10">
                        <div class=" ticker tick tickerBG bn-news">
                            <ul class="" id="dynamicTicker">


                            </ul>
                        </div>
                        <div class="ticker tick tickerBG " id="dynamicTickerRed">
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

    <script>
        let latest_video = '';
        let preloaded_video = '';
        let bool_latest_video = false;

        var news = [];
        let boolLoad = false;
        let mute = true;
        let channel_id_count = 1;
        let per_day_hour_count = false;
        let latestSlot = {};
        let boolSlot = false;
        let videoStartTime = 0;
        let videoCount = 0
        let slotCount = 0
        var slotSource = [];
        var videoSource = [{
                video_path: 'https://player.vimeo.com/video/696497889?autoplay=1&muted=1&autopause=0',
                ticker_text: [
                    'پنجاب سیف سٹیز اتھارٹی نے ون فائیو(15) ہیلپ لائن کالز ریکارڈ جاری کردیا',
                    'اپریل میں لاہور سمیت پنجاب بھر سے 23لاکھ 69 ہزار 212 کالز موصول ہوئیں',
                    'مختلف معلومات کے حصول کیلئے35 ہزار211 کالز موصول ہوئیں'
                ],
                video_type_id: '3',
                video_info: '1st id'
            },
            {
                video_path: 'https://player.vimeo.com/video/700365166?autoplay=1&muted=1&autopause=0',
                ticker_text: [
                    'پنجاب سیف سٹیز اتھارٹی نے ون فائیو(15) ہیلپ لائن کالز ریکارڈ جاری کردیا',
                    'اپریل میں لاہور سمیت پنجاب بھر سے 23لاکھ 69 ہزار 212 کالز موصول ہوئیں',
                    'مختلف معلومات کے حصول کیلئے35 ہزار211 کالز موصول ہوئیں'
                ],
                video_type_id: '3',
                video_info: '1st id'
            },
            {
                video_path: 'https://player.vimeo.com/video/708872559?autoplay=1&muted=1&autopause=0',
                ticker_text: [
                    'اپریل کے مہینے میں ٹریفک سے متعلقہ 10 ہزار 163  کالز پر راہنمائی کی گئی',
                    'لاہورلاسٹ اینڈ فاؤنڈ سینٹرکی مدد سے 06افراد کوتلاش کرکے اپنوں سے ملوایا گیا',
                    'لاہور میں 153 موٹرسائیکل، 7 گاڑیاں  مالکان کے حوالے کیے',
                    'شہری ون فائیو (15) ایمرجنسی ہیلپ لائن پر غیر ضروری کالز سے اجتناب کریں',

                ],
                video_type_id: '3',
                video_info: '1st umar report nomaish ka agaaz'

            },
            {
                video_path: 'https://player.vimeo.com/video/708872559?autoplay=1&muted=1&autopause=0',
                ticker_text: [
                    'اپریل کے مہینے میں ٹریفک سے متعلقہ 10 ہزار 163  کالز پر راہنمائی کی گئی',
                    'لاہورلاسٹ اینڈ فاؤنڈ سینٹرکی مدد سے 06افراد کوتلاش کرکے اپنوں سے ملوایا گیا',
                    'لاہور میں 153 موٹرسائیکل، 7 گاڑیاں  مالکان کے حوالے کیے',
                    'شہری ون فائیو (15) ایمرجنسی ہیلپ لائن پر غیر ضروری کالز سے اجتناب کریں',

                ],
                video_type_id: '3',
                video_info: '1st umar report nomaish ka agaaz'

            },
        ];

        let loadedVideo = ""
        let loadedSecondVideo = ""

        let nextVideoFunc = () => {
            console.log('first', loadedVideo)
            document.getElementById("embedVideo").style.display = 'none'
            document.getElementById("embedPreloadVideo").style.display = 'flex'
            loadedVideo.play();
            loadedVideo.on('ended', function() {
                videoCount++;
                videoLoop()
            })
            document.getElementById("embedVideo").innerHTML = '<iframe src="' +
                `${videoSource[videoCount+1].video_path}` +
                '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="videos"></iframe>'
            let videoUrl = document.getElementById("videos");
            loadedSecondVideo = new Vimeo.Player(videoUrl);
            loadedSecondVideo.loadVideo()
            console.log('jjjjj', loadedSecondVideo)
        }

        let videoLoop = () => {
            document.getElementById("embedPreloadVideo").style.display = 'none'
            document.getElementById("embedVideo").style.display = 'flex'
            if (loadedSecondVideo == "") {
                document.getElementById("embedVideo").innerHTML = '<iframe src="' +
                    `${videoSource[videoCount].video_path}?autoplay=1&muted=1&#t=0` +
                    '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="videos"></iframe>'

                document.getElementById("embedPreloadVideo").innerHTML = '<iframe src="' +
                    `${videoSource[videoCount+1].video_path}` +
                    '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="videosLoad"></iframe>'
            } else {
                loadedSecondVideo.play()
                loadedSecondVideo.on('ended', function() {

                    videoCount++;
                    nextVideoFunc()

                })

                document.getElementById("embedPreloadVideo").innerHTML = '<iframe src="' +
                    `${videoSource[videoCount+1].video_path}` +
                    '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="videosLoad"></iframe>'
            }
            console.log('kkkkoodfdfdf', loadedSecondVideo)

            let videoUrl = document.getElementById("videos");
            let secondVideo = document.getElementById("videosLoad");

            var player = new Vimeo.Player(videoUrl);

            loadedVideo = new Vimeo.Player(secondVideo);
            loadedVideo.loadVideo();
            document.getElementById("videos").innerHTML =
                `<div class="ticker__item"><span class="block"></span>  ${videoSource[videoCount].ticker_text}    </span></div>`


            player.on('ended', function() {

                videoCount++;
                nextVideoFunc()

            })

        }

        const element = document.getElementById("videos");

        function playVid() {
            if (element.paused) element.play();
            else element.pause();
        }
        // let volumeHandler = () => {
        //     let videoUrl = document.getElementById("videos");
        //     let volumeToggle = document.getElementById("volume_toggle");
        //     if (videoUrl != null) {
        //         if (videoUrl.muted == true) {
        //             volumeToggle.innerHTML = '<i class="fa fa-volume-up" aria-hidden="true"></i>'
        //             videoUrl.muted = false;
        //             secondVideo.muted = false;
        //         } else if (videoUrl.muted == false) {
        //             volumeToggle.innerHTML = '<i class="fa fa-volume-off" aria-hidden="true"></i>'
        //             videoUrl.muted = true
        //             secondVideo.muted = true;

        //         }
        //     } else {
        //         return
        //     }
        // }
        let volumeHandler = () => {
            let videoUrl = document.getElementById("videos");
            let volumeToggle = document.getElementById("volume_toggle");
            if (videoUrl != null) {
                if (mute == true) {
                    volumeToggle.innerHTML = '<i class="fa fa-volume-up" aria-hidden="true"></i>'
                    var player = new Vimeo.Player(videoUrl);
                    mute = false;
                    player.setVolume(1);
                    // player.on('pause', player.play());

                } else if (mute == false) {
                    volumeToggle.innerHTML = '<i class="fa fa-volume-off" aria-hidden="true"></i>'
                    var player = new Vimeo.Player(videoUrl);
                    mute = true;
                    player.setVolume(0);

                    // player.on('pause', player.play());
                }
            } else {
                return
            }
        }


        let secondVideo = document.getElementById("secondVideo");

        $(document).ready(function() {
            boolLoad = true;
            videoLoop();
        });

        setInterval(function() {
            // toggle the class every five second
            $(".animatedBreaking").toggleClass("animatedTick");

        }, 10000);
    </script>

    <script src="/modules/news/jquery.js"></script>

</body>

</html>
