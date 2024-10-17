<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\News\Entities;
use Carbon\Carbon;
use Response;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
class ChannelController extends Controller
// din-live-channel
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $channels =  Entities\Channel::all();
        return $channels;
    }
    public function channelStream()
    {
        $channels =  Entities\Channel::all();
        return view('news::channel', compact('channels'));
    }
    

    public function demoChannel()
    {
        $channels =  Entities\Channel::all();
        return view('news::din-live-channel', compact('channels'));
    }

    public function channelTicker($channel_id)
    {
        $tickers = Entities\Ticker::where('channel_id',$channel_id)
        ->where('status','approve')
        ->get();
        return  $tickers;
    }
    
    public function channelStreamOnVimeo()
    {
        return view('news::din-media-vimeo');
    }
    public function channelAjax($channel_id)
    {
        $count = 0;
        $news = [];
        $videoStartTime = 0;
        date_default_timezone_set("Asia/Karachi");
        $current_date_time = Carbon::now();
        $current_date = Carbon::parse($current_date_time);
       
        $channel = Entities\Channel::where('id',$channel_id)->first();
       
        $slots = Entities\Slot::where('channel_id',$channel->id)
        ->join('channels', 'channels.id','=','slots.channel_id')
        ->join('slot_types', 'slot_types.id','=','slots.slot_type_id')
        ->join('archives', 'archives.id','=','slots.archive_id')
        ->selectRaw('slots.*')
        ->selectRaw('archives.video_path as video_path')
        ->whereDate('slots.start_date', Carbon::today())
        ->whereTime('slots.start_time', '>=', Carbon::now()->toTimeString())
        ->get();

        $channel_has_video_loops = Entities\ChannelHasVideoLoop::where('channel_id',$channel->id)
        ->selectRaw('channel_has_video_loops.*')
        ->first();

        if(!empty($channel_has_video_loops))
        {

            $loop_start = Carbon::parse($channel_has_video_loops->start_time);
            $diff = $current_date->diffInSeconds($loop_start);
            $news = Entities\ChannelHasVideo::where('channel_id',$channel->id)
            ->join('news', 'news.id','=','channel_has_videos.video_id')
            ->orderBy('news.id', 'DESC')
            ->selectRaw('news.*')->where('status',"approve")->get();
            // $videoStartTime = $diff;
            $videoStartTime = 0;
         
            // for ($i=0; $i < floor($diff/2) ; $i++) { 
            //     foreach($getNews as $new){
            //         $videoStartTime = $videoStartTime - $new->news_duration;
            //         if( $videoStartTime < 0 ){
            //             $news = Entities\ChannelHasVideo::where('channel_id',$channel->id)
            //             ->join('news', 'news.id','=', 'channel_has_videos.video_id')
            //             ->where('news.id', '>=', $new->id)
            //             ->selectRaw('news.*')
            //             ->where('status',"approve")->get();
            //             $count++;
            //             $videoStartTime = $videoStartTime*(-1);
    
            //             break;
            //         }
            //         else if( $videoStartTime == 0 ){
            //             $news = Entities\News::where('id', '>=', $new->id )
            //             ->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id','ticker_duration')->get();
            //             $count++;
    
            //             break;
            //         }
            //         // return $videoStartTime;
            //     }
            //     if($count > 0){
            //         break;
            //     }
    
            // }
        }

        // return $videoStartTime.'cur'.$current_date.'st'.$loop_start.'didff'.$diff.'ddfdf'.$news;
        return  Response::json(['news' => $news, 
                        'videoStartTime' => $videoStartTime,
                        'slots' => $slots,
                ]);
    }

    public function getChannelList()
    {
        return view('news::channel_list');

    }
    public function getChannelByID($channel_id)
    {
        $url = Str::lower(substr(strrchr(url()->current(), '/'), 1));
        $channel = Entities\Channel::where('url', 'LIKE', "%" . $url . "%")->first();
   
        return view('news::din-media-network', compact('channel'));
    }

    //...................................


    // public function channelAjax($channel_id)
    // {
    //     date_default_timezone_set("Asia/Karachi");
    //     $current_date_time = Carbon::now();
    //     $current_date = Carbon::parse($current_date_time);
       
    //     $channel = Entities\Channel::where('id',$channel_id)->first();
    //     $channel_ticker_loop  = Entities\ChannelHasTickerLoop::where('channel_id',$channel->id)
    //     ->selectRaw('channel_has_ticker_loops.*')
    //     ->first();
        
    //     $loop_ticker_start = Carbon::parse($channel_ticker_loop->start_time);
    //     $diff_ticker = $current_date->diffInSeconds($loop_ticker_start);

    //     $getNewsTicker = Entities\ChannelHasVideo::where('channel_id',$channel_ticker_loop->channel_id)
    //     ->join('news', 'news.id','=','channel_has_videos.video_id')
    //     ->orderBy('news.id', 'DESC')
    //     ->selectRaw('news.*')->where('status',"approve")->get();
    //     $slots = Entities\Slot::where('channel_id',$channel->id)
    //     ->join('channels', 'channels.id','=','slots.channel_id')
    //     ->join('slot_types', 'slot_types.id','=','slots.slot_type_id')
    //     ->join('archives', 'archives.id','=','slots.archive_id')
    //     ->selectRaw('slots.*')
    //     ->selectRaw('archives.video_path as video_path')
    //     ->whereDate('slots.start_date', Carbon::today())
    //     ->whereTime('slots.start_time', '>=', Carbon::now()->toTimeString())
    //     ->get();
    //     // return Carbon::now()->toTimeString();
    //     $tickerStartTime = $diff_ticker;
    //     $tickerCount = 0;
    //     $newsTicker = [];


    //     for ($i=0; $i < floor($diff_ticker/2) ; $i++) { 
    //         foreach($getNewsTicker as $new){
    //             $tickerStartTime = $tickerStartTime - $new->news_duration;
    //             if( $tickerStartTime < 0 ){
    //                 $newsTicker = Entities\ChannelHasVideo::where('channel_id',$channel_ticker_loop->channel_id)
    //                 ->join('news', 'news.id','=', 'channel_has_videos.video_id')
    //                 ->where('news.id', '>=', $new->id)
    //                 ->selectRaw('news.*')->where('status',"approve")->get();
    //                 $tickerCount++;
    //                 $tickerStartTime = $tickerStartTime*(-1);

    //                 break;
    //             }
    //             else if( $tickerStartTime == 0 ){
    //                 $newsTicker = Entities\News::where('id', '>=', $new->id )
    //                 ->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id','ticker_duration')->get();
    //                 $tickerCount++;

    //                 break;
    //             }
    //             // return $videoStartTime;
    //         }
    //         if($tickerCount > 0){
    //             break;
    //         }

    //     }




    //     $channel_has_video_loops = Entities\ChannelHasVideoLoop::where('channel_id',$channel->id)
    //     ->selectRaw('channel_has_video_loops.*')
    //     ->first();


    //     $loop_start = Carbon::parse($channel_has_video_loops->start_time);
    //     $diff = $current_date->diffInSeconds($loop_start);
 

    //     $getNews = Entities\ChannelHasVideo::where('channel_id',$channel->id)
    //     ->join('news', 'news.id','=','channel_has_videos.video_id')
    //     ->orderBy('news.id', 'DESC')
    //     ->selectRaw('news.*')->where('status',"approve")->get();
    //     $videoStartTime = $diff;
    //     $count = 0;
    //     $news = [];
    //     for ($i=0; $i < floor($diff/2) ; $i++) { 
    //         foreach($getNews as $new){
    //             $videoStartTime = $videoStartTime - $new->news_duration;
    //             if( $videoStartTime < 0 ){
    //                 $news = Entities\ChannelHasVideo::where('channel_id',$channel->id)
    //                 ->join('news', 'news.id','=', 'channel_has_videos.video_id')
    //                 ->where('news.id', '>=', $new->id)
    //                 ->selectRaw('news.*')->where('status',"approve")->get();
    //                 $count++;
    //                 $videoStartTime = $videoStartTime*(-1);

    //                 break;
    //             }
    //             else if( $videoStartTime == 0 ){
    //                 $news = Entities\News::where('id', '>=', $new->id )
    //                 ->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id','ticker_duration')->get();
    //                 $count++;

    //                 break;
    //             }
    //             // return $videoStartTime;
    //         }
    //         if($count > 0){
    //             break;
    //         }

    //     }

    //     // return $videoStartTime.'cur'.$current_date.'st'.$loop_start.'didff'.$diff.'ddfdf'.$news;
    //     return  Response::json(['news' => $news, 
    //                     'videoStartTime' => $videoStartTime,
    //                     'newsTicker' => $newsTicker,
    //                     'tickerStartTime' => $tickerStartTime,
    //                     'channel_ticker_loop' => $channel_ticker_loop,
    //                     'slots' => $slots
    //             ]);
    // }


    // public function channelAjax($id)
    // {
    //     date_default_timezone_set("Asia/Karachi");
    //     $current_date_time = Carbon::now();
    //     $newsLoop = Entities\NewsLoop::where('id',1)->first();
    //     $current_date = Carbon::parse($current_date_time);
    //     $loop_start = Carbon::parse($newsLoop->start_time);
    //     $diff = $current_date->diffInSeconds($loop_start);
 

    //     $getNews = Entities\News::where('genre_id',$id)
    //     ->orderBy('id', 'DESC')->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id')->where('status',"approve")->get();
    //     $videoStartTime = $diff;
    //     $count = 0;
    //     $news = [];
    //     for ($i=0; $i < floor($diff/2) ; $i++) { 
    //         foreach($getNews as $new){
    //             $videoStartTime = $videoStartTime - $new->news_duration;
    //             if( $videoStartTime < 0 ){
    //                 $news = Entities\News::where('id', '>=', $new->id )->where('genre_id',$id)
    //                 ->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id')->where('status',"approve")->get();
    //                 $count++;
    //                 $videoStartTime = $videoStartTime*(-1);

    //                 break;
    //             }
    //             else if( $videoStartTime == 0 ){
    //                 $news = Entities\News::where('id', '>=', $new->id )
    //                 ->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id')->get();
    //                 $count++;

    //                 break;
    //             }
    //             // return $videoStartTime;
    //         }
    //         if($count > 0){
    //             break;
    //         }

    //     }

    //     // return $videoStartTime.'cur'.$current_date.'st'.$loop_start.'didff'.$diff.'ddfdf'.$news;
    //     return Response::json(['news' => $news, 'videoStartTime' => $videoStartTime]);
    // }
    public function store(Request $request)
    {
        $extension =  $request->file('logo')->extension();
        $logo = Storage::disk('digitalocean')->putFile('uploads', $request->file('logo'),'public');

        $channel = Entities\Channel::create([
            'name' => $request->input('name'),
            'genre_id' => $request->input('genre_id'),
            'city_id' =>  $request->input('city_id'),
            'logo' =>  $logo,
            'url' =>  "/news/channel/".Str::lower($request->input('name')),
        ]);
        return $channel;
    }

    public function ajaxIndex()
    {
        date_default_timezone_set("Asia/Karachi");
        $current_date_time = Carbon::now();
        $newsLoop = Entities\NewsLoop::where('id',1)->first();
        $current_date = Carbon::parse($current_date_time);
        $loop_start = Carbon::parse($newsLoop->start_time);
        $diff = $current_date->diffInSeconds($loop_start);
 

        $getNews = Entities\News::orderBy('id', 'DESC')->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id','ticker_duration')->where('status',"approve")->get();
        $videoStartTime = $diff;
        $count = 0;
        $news = [];
        for ($i=0; $i < floor($diff/2) ; $i++) { 
            foreach($getNews as $new){
                $videoStartTime = $videoStartTime - $new->news_duration;
                if( $videoStartTime < 0 ){
                    $news = Entities\News::where('id', '>=', $new->id )
                    ->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id','ticker_duration')->where('status',"approve")->get();
                    $count++;
                    $videoStartTime = $videoStartTime*(-1);

                    break;
                }
                else if( $videoStartTime == 0 ){
                    $news = Entities\News::where('id', '>=', $new->id )
                    ->select('id','news_duration','video_name', 'video_path', 'ticker_text','video_type_id')->get();
                    $count++;

                    break;
                }
                // return $videoStartTime;
            }
            if($count > 0){
                break;
            }

        }

      
        return Response::json(['news' => $getNews, 'videoStartTime' => $videoStartTime]);
      
    }

    public function update($id, Request $request)
    {
        if($request->file('logo')){

            $extension =  $request->file('logo')->extension();
            $request['logo'] = Storage::disk('digitalocean')->putFile('uploads', $request->file('logo'),'public');
        }
        $channel = Entities\Channel::where('id',$id)->first();
        if($channel->url == null){
            $request['url'] =  "/news/channel/".Str::lower( $channel->name);
        }
        $channel->update($request->input());

        return $channel;
    }
}
