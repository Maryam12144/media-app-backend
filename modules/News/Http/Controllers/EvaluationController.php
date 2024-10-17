<?php

namespace Modules\News\Http\Controllers;

use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\News\Entities;
use App\Events\PendingVideo;
use Carbon\Carbon;
use App\Events\EvaluateVideo;
use App\Mail\MailVideoInfo;
use Illuminate\Support\Str;
use App\User;
use Mail;
class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('news::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('news::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $news =  Entities\News::where('id' ,  $request->news_id)->first(); 
       
        $news->status = $request->status;
        $news->news_duration = $request->duration;
        $news->update();
        $evaluation = Entities\Evaluation::where('news_id' ,  $news->id)->first();
        $evaluation->criteria = $request->criteria;
        $evaluation->update();

        $newsEvaluate = Entities\Evaluation::where('news_id', $evaluation->news_id)
        ->where('criteria','!=','')
        ->leftJoin('news', 'news.id','=','evaluations.news_id')
        ->where('news.status','!=','pending')
        ->selectRaw('evaluations.criteria as criteria')
        ->selectRaw('evaluations.poster_id as poster_id')
        ->selectRaw('evaluations.id as evaluation_id')
         ->selectRaw('news.*')
         ->leftJoin('users', 'users.id','=','news.user_id')
         ->selectRaw('users.full_name as full_name')
         ->leftJoin('genres', 'genres.id','=','news.genre_id')
         ->selectRaw('genres.name as genre_name')
         ->first();
         $userInfo = User::where('id', $news->user_id)->first();
    
        if($news->status == "approve")
        {
            $newsLoop =  Entities\NewsLoop::where('id' , 1)->first();

            $newsLoop->loop_duration = $newsLoop->loop_duration + $request->input('duration');
            $newsLoop->update();

            $channel_video =  Entities\ChannelHasVideo::where('video_id' , $news->id)
            ->join('channels', 'channels.id','=','channel_has_videos.channel_id')
            ->selectRaw('channel_has_videos.*')
            ->selectRaw('channels.*')
            ->first();
            $current_date_time = Carbon::now();
            $channel_has_video_loop_exist = Entities\ChannelHasVideoLoop::where('channel_id',$channel_video->channel_id)
            ->first();
            if($channel_has_video_loop_exist)
            {
                $channel_has_video_loop_exist->video_duration =  $channel_has_video_loop_exist->video_duration + $request->input('duration');
                $channel_has_video_loop_exist->update();
            }
            else{
                     Entities\ChannelHasVideoLoop::create([
                    'video_duration' => $request->input('duration'),
                    'channel_id' => $channel_video->channel_id,
                    'start_time' => $current_date_time
                ]);
            }

            $count_ticker = Str::of($news->ticker_text)->wordCount();
            $ticker_division =  $count_ticker/20;
    
            $count_ticker_duration = floor($ticker_division)*20;
            $subtract_count =  $count_ticker - $count_ticker_duration;
            if($subtract_count > 0){
                $count_ticker_duration = $count_ticker_duration + 20;
            }
            $channel_has_ticker_loop_exist = Entities\ChannelHasTickerLoop::where('channel_id',$channel_video->channel_id)
            ->first();
            if($channel_has_ticker_loop_exist)
            {
                $channel_has_ticker_loop_exist->ticker_duration =  $channel_has_ticker_loop_exist->ticker_duration + $count_ticker_duration;
                $channel_has_ticker_loop_exist->update();
            }
            else{
                $channel_has_ticker_loop = Entities\ChannelHasTickerLoop::create([
                    'ticker_duration' => $count_ticker_duration,
                    'channel_id' => $channel_video->channel_id,
                    'start_time' => $current_date_time
                ]);
            }
            broadcast(new PendingVideo($news,'',''));
            broadcast(new EvaluateVideo(json_encode($newsEvaluate),"Your video has been approved", $news->user_id, $channel_video->name ));
            // return (new MailVideoInfo("sdssddd"))
            // ->to("mobeen.farooq@usa.edu.pk")
            // ->subject('Verify Your Account - detail');
            $data = array('name'=> $channel_video->name,
            );
            Mail::send('emails.video-info-mail', $data,  function ($m) use ($userInfo) {
                $m->from('ali.kamran4477696@gmail.com', 'Your Application');
     
                $m->to($userInfo->email, "Kanact Media")->subject('Your Video has been uploaded to');
            });
        }
        else {
            if($newsEvaluate->status == 'modify')
            {
                broadcast(new EvaluateVideo(json_encode($newsEvaluate),"You have a new video to modify",$newsEvaluate->poster_id, "" ));

            }
            else {
                broadcast(new EvaluateVideo(json_encode($newsEvaluate),"Your video has been rejected",$news->user_id, "" ));
            }
        }
        return $evaluation;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('news::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('news::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
