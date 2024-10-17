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
        <!-- <div id="embedVideo"  class="mainVideo"></div> -->
        <video class="mainVideo" preload src="" id="videos" muted crossorigin playsinline
        >
        </video>

        <video class="mainVideo" preload src="" id="secondVideo" muted crossorigin playsinline
        >
        </video>
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
                video_path: 'uploads/rPkDxuk4qnikQYwimyRrakYXeSMPzZJFoBitwsIv.mp4',
                ticker_text: [
                    'پنجاب سیف سٹیز اتھارٹی نے ون فائیو(15) ہیلپ لائن کالز ریکارڈ جاری کردیا',
                    'اپریل میں لاہور سمیت پنجاب بھر سے 23لاکھ 69 ہزار 212 کالز موصول ہوئیں',
                    'مختلف معلومات کے حصول کیلئے35 ہزار211 کالز موصول ہوئیں'
                ],
                video_type_id: '3',
                video_info: '1st id'
            },
            {
                video_path: 'uploads/MzP2SeaI9fDownTI8e0fcM7EjX0Cd4NxeHkkiHyl.mp4',
                ticker_text: [
                    'اپریل کے مہینے میں ٹریفک سے متعلقہ 10 ہزار 163  کالز پر راہنمائی کی گئی',
                    'لاہورلاسٹ اینڈ فاؤنڈ سینٹرکی مدد سے 06افراد کوتلاش کرکے اپنوں سے ملوایا گیا',
                    'لاہور میں 153 موٹرسائیکل، 7 گاڑیاں  مالکان کے حوالے کیے',
                    'شہری ون فائیو (15) ایمرجنسی ہیلپ لائن پر غیر ضروری کالز سے اجتناب کریں',
                    'ملک میں بجلی کے بعد گیس بحران سنگین صورت اختیار کر گیا',
                    'سوئی ناردرن کے سسٹم پر موسم گرما کے باوجود گیس قلت برقرار ہے',
                    'سی این جی ایسوسی ایشن کا گیس فراہمی معطل ہونے پر شدید احتجاج',
                    'قلت کے باعث گھریلو صارفین کو بھی صرف دن کے اوقات میں گیس فراہم کی جارہی ہے',
                    'پاور سیکٹر کو سپلائی میں کمی جبکہ سی این جی سیکٹر کو فراہمی معطل کر دی گئی',
                ],
                video_type_id: '3',
                video_info: '1st umar report nomaish ka agaaz'

            },
            {
                video_path: 'uploads/NKRs1ueSxfizsPTgdo76s0O5R3gVqCa2jvpYO1Bo.mp4',
                ticker_text: [
                    'پنجاب سیف سٹیز اتھارٹی نے ون فائیو(15) ہیلپ لائن کالز ریکارڈ جاری کردیا',
                    'لاہورلاسٹ اینڈ فاؤنڈ سینٹرکی مدد سے 06افراد کوتلاش کرکے اپنوں سے ملوایا گیا',
                    'لاہور میں 153 موٹرسائیکل، 7 گاڑیاں  مالکان کے حوالے کیے',
                ],
                video_type_id: '3',
                video_info: '2nd id'
            },

            //DykT9TBfFd7L4kJqIHKVwNqUlBwPQWqmixThoqAg
            {
                video_path: 'uploads/2IO3XMuqu0vlzmRscKGJUx4Q8vilQQx0DiJSqpuS.mp4',
                ticker_text: [
                    'ملک میں بجلی کے بعد گیس بحران سنگین صورت اختیار کر گیا',
                    'بحران کے باعث سوئی ناردرن نے سی این جی سیکٹر کو گیس فراہمی غیر معینہ مدت تک معطل کر دی',
                    'سوئی ناردرن کے سسٹم پر موسم گرما کے باوجود گیس قلت برقرار ہے',
                    'قلت کے باعث گھریلو صارفین کو بھی صرف دن کے اوقات میں گیس فراہم کی جارہی ہے',
                    'پاور سیکٹر کو سپلائی میں کمی جبکہ سی این جی سیکٹر کو فراہمی معطل کر دی گئی',
                    'سی این جی ایسوسی ایشن کا گیس فراہمی معطل ہونے پر شدید احتجاج',
                    'لاہور :شہر میں چوری ، ڈکیتی کی وارداتوں میں خوفناک حدتک اضافہ',
                    'لاہور: تھانہ ستوکتلہ کی حدود میں موبائل شاپ پر چوری کی واردات',
                    'لاہور: واردات کی سی سی ٹی وی فوٹیج دن نیوز کو موصول',
                    'لاہور:  فوٹیج میں چور کو دوکان میں چوری کرتے ہوئیں  دیکھا جا سکتا ہے',
                ],
                video_type_id: '3',
                video_info: '2st loadshaiding'
            },


            {
                video_path: 'uploads/CBt1vuOYWhrhb84MLryvt3Iz040p7Mi9lLcI4ZnI.mp4',
                ticker_text: [
                    'اپریل میں لاہور سمیت پنجاب بھر سے 23لاکھ 69 ہزار 212 کالز موصول ہوئیں',
                    'مختلف معلومات کے حصول کیلئے35 ہزار211 کالز موصول ہوئیں'
                ],
                video_type_id: '3',
                video_info: '3rd id'
            },
            {
                video_path: 'uploads/KX6qCIdERLxIc12QxgkdVKpinq5iSszB80DCmqSU.mp4',
                ticker_text: [
                    'لاہور: چور دوکان سے موبائل ، تقدی اور قیمتی سامان لے جاتے ہوئیں دیکھا جاسکتا ہے ، فوٹیج',
                    'لاہور: واردات کا مقدمہ تھانہ ستوکتلہ میں درج کر لیا گیا ہے ، پولیس',
                    'لاہور : ملزمان کی تلاش جاری ہے جلد گرفتار کر لیا جائے گا ، پولیس',
                    'الحمراء میں یوم تکبیر کی مناسبت سے مقابلہ مصوری و نمائش  کی اختتامی تقریب',
                    'قومی جوہری پروگرام کے معماروں کا آرٹسٹوں کا خراج تحسین پیش',
                    'مقابلہ میں ملک بھر سے 117آرٹسٹوں کے 130فن پاروں کی نمائش کی گئی',
                    'مقابلہ میں ماہ چوہدری کی پہلی،بابر مصطفی کی دوسری جبکہ ہوزیلا زاہد نے تیسری پوزیشن حاصل کی',
                    'وطن عزیز کی حفاظت کرنے والوں کی عظمت کو سلام پیش کرتے ہیں۔ ایگزیکٹوڈائریکٹر الحمراء',
                    'آرٹ کے ذریعے نئی نسل کو اپنی عظمت ِرفتہ کے سفر سے آگا ہ کر رہے ہیں۔ ذوالفقار علی زلفی',
                    'پاکستان کے ناقابل تسخیر ہونے کو آرٹسٹوں شاندار انداز میں پینٹ کیا۔ذوالفقارعلی زلفی',
                    '28 مئی 1998 کو ملک ایٹمی طاقت بنا',
                ],
                video_type_id: '3',
                video_info: '3rd  report 15 call'
            },


            {
                video_path: 'uploads/NKRs1ueSxfizsPTgdo76s0O5R3gVqCa2jvpYO1Bo.mp4',
                ticker_text: [
                    'اپریل میں لاہور سمیت پنجاب بھر سے 23لاکھ 69 ہزار 212 کالز موصول ہوئیں',
                    'اپریل کے مہینے میں ٹریفک سے متعلقہ 10 ہزار 163  کالز پر راہنمائی کی گئی',
                ],
                video_type_id: '3'
                ,
                video_info: '4th id'
            },
            {
                video_path: 'uploads/QTLGwM0DXvc5YwOq1JzNR0mQHjFkkQ3ecp0g6Te9.mp4',
                ticker_text: [
                    'تقریب کے آخر پر مقابلہ کے تمام شرکاء کو اسناد دی گئیں',
                    'پنجاب سیف سٹیز اتھارٹی نے ون فائیو(15) ہیلپ لائن کالز ریکارڈ جاری کردیا',
                    'اپریل میں لاہور سمیت پنجاب بھر سے 23لاکھ 69 ہزار 212 کالز موصول ہوئیں',
                    'مختلف معلومات کے حصول کیلئے35 ہزار211 کالز موصول ہوئیں',
                    'اپریل کے مہینے میں ٹریفک سے متعلقہ 10 ہزار 163  کالز پر راہنمائی کی گئی',
                    'لاہورلاسٹ اینڈ فاؤنڈ سینٹرکی مدد سے 06افراد کوتلاش کرکے اپنوں سے ملوایا گیا',
                ],
                video_type_id: '3',
                video_info: '4th  report lahore police '
            },
            {
                video_path: 'uploads/CBt1vuOYWhrhb84MLryvt3Iz040p7Mi9lLcI4ZnI.mp4',
                ticker_text: [
                    'ملک میں بجلی کے بعد گیس بحران سنگین صورت اختیار کر گیا',
                    'سی این جی ایسوسی ایشن کا گیس فراہمی معطل ہونے پر شدید احتجاج',
                ],
                video_type_id: '3',
                video_info: '5th  id'
            },
            {
                video_path: 'uploads/Clm4HjPDqMZhij2ImPRt3k66CVVM07X0agFrcS4I.mp4',
                ticker_text: [
                    'لاہور میں 153 موٹرسائیکل، 7 گاڑیاں  مالکان کے حوالے کیے',
                    'شہری ون فائیو (15) ایمرجنسی ہیلپ لائن پر غیر ضروری کالز سے اجتناب کریں',
                    'ملک میں بجلی کے بعد گیس بحران سنگین صورت اختیار کر گیا',
                    'بحران کے باعث سوئی ناردرن نے سی این جی سیکٹر کو گیس فراہمی غیر معینہ مدت تک معطل کر دی',
                    'سوئی ناردرن کے سسٹم پر موسم گرما کے باوجود گیس قلت برقرار ہے',
                    'قلت کے باعث گھریلو صارفین کو بھی صرف دن کے اوقات میں گیس فراہم کی جارہی ہے',
                    'پاور سیکٹر کو سپلائی میں کمی جبکہ سی این جی سیکٹر کو فراہمی معطل کر دی گئی',
                ],
                video_type_id: '3',
                video_info: '5th  report talib ilmon'
            },
            {
                video_path: 'uploads/NKRs1ueSxfizsPTgdo76s0O5R3gVqCa2jvpYO1Bo.mp4',
                ticker_text: [
                    'پاور سیکٹر کو سپلائی میں کمی جبکہ سی این جی سیکٹر کو فراہمی معطل کر دی گئی',
                    'قلت کے باعث گھریلو صارفین کو بھی صرف دن کے اوقات میں گیس فراہم کی جارہی ہے',
                ],
                video_type_id: '3',
                video_info: '6th  id'
            },
            {
                video_path: 'uploads/eB6JUMhCqRwjG7DqQuQX3T2XGe2iUFw1pi7colmq.mp4',
                ticker_text: [
                    'لاہور: چور دوکان سے موبائل ، تقدی اور قیمتی سامان لے جاتے ہوئیں دیکھا جاسکتا ہے ، فوٹیج',
                    'لاہور: واردات کا مقدمہ تھانہ ستوکتلہ میں درج کر لیا گیا ہے ، پولیس',
                    'لاہور : ملزمان کی تلاش جاری ہے جلد گرفتار کر لیا جائے گا ، پولیس',
                    'الحمراء میں یوم تکبیر کی مناسبت سے مقابلہ مصوری و نمائش  کی اختتامی تقریب',
                    'قومی جوہری پروگرام کے معماروں کا آرٹسٹوں کا خراج تحسین پیش',
                    'مقابلہ میں ملک بھر سے 117آرٹسٹوں کے 130فن پاروں کی نمائش کی گئی',
                    'مقابلہ میں ماہ چوہدری کی پہلی،بابر مصطفی کی دوسری جبکہ ہوزیلا زاہد نے تیسری پوزیشن حاصل کی',
                    'وطن عزیز کی حفاظت کرنے والوں کی عظمت کو سلام پیش کرتے ہیں۔ ایگزیکٹوڈائریکٹر الحمراء',
                    'آرٹ کے ذریعے نئی نسل کو اپنی عظمت ِرفتہ کے سفر سے آگا ہ کر رہے ہیں۔ ذوالفقار علی زلفی',
                    'پاکستان کے ناقابل تسخیر ہونے کو آرٹسٹوں شاندار انداز میں پینٹ کیا۔ذوالفقارعلی زلفی',
                    '28 مئی 1998 کو ملک ایٹمی طاقت بنا',
                    'ملک میں بجلی کے بعد گیس بحران سنگین صورت اختیار کر گیا',
                ],
                video_type_id: '3',
                video_info: '6th  report cholistan'
            },
            {
                video_path: 'uploads/XfNcOZo30SHsea5rgxRPfqLd4uTexTNBDANbASzT.mp4',
                ticker_text: [
                    'لاہور :شہر میں چوری ، ڈکیتی کی وارداتوں میں خوفناک حدتک اضافہ',
                    'لاہور: تھانہ ستوکتلہ کی حدود میں موبائل شاپ پر چوری کی واردات',
                ],
                video_type_id: '3',
                video_info: '7th id'
            },
            {
                video_path: 'uploads/BFiHK7w70IyaQfg2OhQpyfVT9zUfvbd9s5DStUyc.mp4',
                ticker_text: ['اداروں کو ملک کی تباہی روکنے میں اپنا کردار ادا کرنا ہوگا:  شاہ محمود قریشی',
                'عدالتی فیصلے پاکستان کی سیاست میں اہم موڑ ثابت ہوں گے: چودھری پرویزالٰہی، شاہ محمود قریشی',
                'ضمنی الیکشن میں پی ٹی آئی فوری طور پر اپنے امیدواروں کا اعلان کرے: چوہدری پرویز الہی',
                'لوٹوں کو کیفر کردار تک پہنچانے میں ہم عمران خان کی بھرپور سپورٹ کریں گے: چوہدری پرویز الہی',
                'ملاقات میں موجودہ ملکی سیاسی صورتحال سمیت پنجاب اور باہمی دلچسپی کے امور پر تبادلہ خیال',
                'پاکستان تحریک انصاف اور پاکستان مسلم لیگ کا ساتھ مل کر حکومت کے خلاف جدوجہد کرنے پر اتفاق',
                'ملاقات میں سابق وفاقی وزیر مونس الٰہی اور ایم این اے زین حسین قریشی بھی موجود تھے'

                ],
               
                video_type_id: '3',
                video_info: '7th  chaudhary pervaiz ilahi'
            },
            {
                video_path: 'uploads/XfNcOZo30SHsea5rgxRPfqLd4uTexTNBDANbASzT.mp4',
                ticker_text: [
                    'لاہور :شہر میں چوری ، ڈکیتی کی وارداتوں میں خوفناک حدتک اضافہ',
                    'لاہور: تھانہ ستوکتلہ کی حدود میں موبائل شاپ پر چوری کی واردات',
                ],
                video_type_id: '3',
                video_info: '8th id'
            },
            {
                video_path: 'uploads/mzsgNfdaKtIyhJ8Q56jpx5OQ5gGzOeeJZxvgeHih.mp4',
                ticker_text: [
                    'سابق وزیر صحت ڈاکٹر یاسمین راشد دور کی ایک اور نااہلی سامنے آگئی ',
                    'پنجاب بھرکے ہزاروں بےروزگار ڈاکٹرز کو نوکریاں نہ مل سکی',
                    'پنجاب بھر کے ہزاروں  ڈاکٹرز نوکریوں سے محروم',
                    'بےروزگار کے باعث ڈاکٹرز میں بے چینی اور مایوسی بڑھنے لگی',
                    'میو اسپتال لاہور میں صرف ڈاکٹرز کی ایڈہاک 107 سیٹوں کیلئے 11 ہزار سے زائد درخواستیں آگئیں۔',
                    'ایک سیٹ پر 103 ڈاکٹرز میدان میں۔',
                    'ایک امیدوار کا پیسے لیکر بھرتیوں کا بھی الزام',
                    'ایڈہاک کی سیٹ کیلئے ساڑھے تین لاکھ ڈیمانڈ کیاجارہاہے',
                    ' امیدوار بھرتیوں کیلئے پیسہ ہی صرف میرٹ ہے، امیدوار',
                    'کچھ ماہ قبل اوکاڑہ میں ایڈہاک کی صرف 13 سیٹوں پر 600 سے زائد ڈاکٹرز نے اپلائی کیاتھا۔',
                ],
                video_type_id: '3',
                video_info: '8th report'
            },
            {
                video_path: 'uploads/CBt1vuOYWhrhb84MLryvt3Iz040p7Mi9lLcI4ZnI.mp4',
                ticker_text: [
                    'سابق وزیر صحت ڈاکٹر یاسمین راشد دور کی ایک اور نااہلی سامنے آگئی ',
                    'پنجاب بھرکے ہزاروں بےروزگار ڈاکٹرز کو نوکریاں نہ مل سکی',
                ],
                video_type_id: '3',
                video_info: '9th id'
            },
            // CBt1vuOYWhrhb84MLryvt3Iz040p7Mi9lLcI4ZnI
            // uploads/BFiHK7w70IyaQfg2OhQpyfVT9zUfvbd9s5DStUyc.mp4

        ];

        let videoLoop = () => {

            videoUrl.setAttribute("src",
                `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}`
            );
            videoUrl.load()
            videoUrl.play()
            secondVideo.style.display = 'none'
            secondVideo.setAttribute("src",
                `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount+1].video_path}`
            );
            secondVideo.load();
            if (videoSource[videoCount].ticker_text != null) {
                if (videoSource[videoCount].video_type_id != 1) {
                    videoSource[videoCount].ticker_text.map((data, index) => {
                        document.getElementById('dynamicTicker').innerHTML +=
                            `<li><a href="#">  ${data} 
                           </a> </li>`
                    })
                    $('#newsTicker10').breakingNews({
                        effect: 'slide-down',
                    });
                } else {
                    videoSource[videoCount].ticker_text.map((data, index) => {
                        document.getElementById('dynamicTicker').innerHTML +=
                            `<li><a href="#">  ${data} 
                           </a> </li>`
                    })
                    $('#newsTicker10').breakingNews({
                        effect: 'slide-down',
                    });
                    // var ticker = document.getElementById('dynamicTickerBlue').innerHTML +=
                    //     '<div class="ticker__item"><span class="block"></span>"' +
                    //     videoSource[videoCount].ticker_text.replace(/["']/g, "") + '"</div>'

                }
            }
            videoUrl.onended = function() {
                document.getElementById('dynamicTicker').innerHTML = ""
                videoCount++;
                if (videoCount == videoSource.length - 1 || videoSource.length == 0) {
                    videoCount = 0;
                } else {
                    if(videoCount + 1 > videoSource.length - 1)
                    {
                        videoCount = 0;
                    }
                    videoUrl.setAttribute("src",
                        `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}`
                    );
                    videoUrl.load()
                }


                if (videoSource[videoCount].ticker_text != null) {
                    if (videoSource[videoCount].video_type_id != 1) {
                        videoSource[videoCount].ticker_text.map((data, index) => {
                            // console.log(data, 'ticer')
                            document.getElementById('dynamicTicker').innerHTML +=
                                `<li><a href="#">  ${data} 
                           </a> </li>`
                        })
                        $('#newsTicker10').breakingNews({
                            effect: 'slide-down',
                        });
                    } else {
                        // console.log(videoSource[videoCount].ticker_text, 'trtprptprptprtprt')
                        videoSource[videoCount].ticker_text.map((data, index) => {
                            // console.log(data, 'ticer')
                            document.getElementById('dynamicTicker').innerHTML +=
                                `<li><a href="#">  ${data} 
                           </a> </li>`
                        })
                        $('#newsTicker10').breakingNews({
                            effect: 'slide-down',
                        });
                        // var ticker = document.getElementById('dynamicTickerBlue').innerHTML +=
                        //     '<div class="ticker__item"><span class="block"></span>"' +
                        //     videoSource[videoCount].ticker_text.replace(/["']/g, "") + '"</div>'

                    }
                }
                // else {
                //     videoCount = 0;
                //     videoLoop();
                // }
                // videoCount += 2;
                console.log('first dfdfdf',videoSource[videoCount].video_info, videoCount)

                videoUrl.style.display = 'none'
                secondVideo.style.display = 'flex'
                // secondVideo.setAttribute("src",
                //     `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount+1].video_path}`
                // );
                secondVideo.play();

            }
            secondVideo.onended = function() {

                videoCount++;
                if (videoCount == videoSource.length - 1 || videoSource.length == 0) {
                    videoCount = 0;
                } else {
                    if(videoCount + 1 > videoSource.length - 1)
                    {
                        videoCount = 0;
                    }
                    secondVideo.setAttribute("src",
                        `https://kanact-media.fra1.cdn.digitaloceanspaces.com/${videoSource[videoCount].video_path}`
                    );
                    secondVideo.load();
                }


                if (videoSource[videoCount].ticker_text != null) {
                    if (videoSource[videoCount].video_type_id != 1) {
                        videoSource[videoCount].ticker_text.map((data, index) => {
                            document.getElementById('dynamicTicker').innerHTML +=
                                `<li><a href="#">  ${data} 
                           </a> </li>`
                        })
                        $('#newsTicker10').breakingNews({
                            effect: 'slide-down',
                        });
                    } else {
                        // console.log(videoSource[videoCount].ticker_text, 'trtprptprptprtprt')
                        videoSource[videoCount].ticker_text.map((data, index) => {
                            document.getElementById('dynamicTicker').innerHTML +=
                                `<li><a href="#">  ${data} 
                           </a> </li>`
                        })
                        $('#newsTicker10').breakingNews({
                            effect: 'slide-down',
                        });
                        // var ticker = document.getElementById('dynamicTickerBlue').innerHTML +=
                        //     '<div class="ticker__item"><span class="block"></span>"' +
                        //     videoSource[videoCount].ticker_text.replace(/["']/g, "") + '"</div>'

                    }
                }
                console.log('second rrrr',videoSource[videoCount].video_info, videoCount)

                videoUrl.style.display = 'flex'
                secondVideo.style.display = 'none'
                videoUrl.play()

            }
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
                    videoUrl.muted = false;
                    secondVideo.muted = false;
                } else if (videoUrl.muted == false) {
                    volumeToggle.innerHTML = '<i class="fa fa-volume-off" aria-hidden="true"></i>'
                    videoUrl.muted = true
                    secondVideo.muted = true;

                }
            } else {
                return
            }
        }

        let videoUrl = document.getElementById("videos");

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
