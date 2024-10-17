<?php

namespace modules\News\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Modules\News\Entities;
use Modules\Core\Http\Controllers\Controller;
use App\Lib\PusherFactory;
use Modules\News\Http\Resources\NewsResource;
use Modules\News\Http\Requests\News as NewsRequests;
use Illuminate\Support\Str;
use Vimeo\Laravel\Facades\Vimeo;
use Carbon\Carbon;
use App\Events\PendingVideo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\User;
use Mail;
use App\Events\EvaluateVideo;


class NewsController extends Controller
{
    public function __construct(Entities\News $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(NewsRequests\NewsShowRequest $request)
    {
        $user = Auth::user()->id;
        $news = Entities\News::where('user_id',$user)->orderBy('id', 'DESC')->select('video_name', 'video_path', 'ticker_text','video_type_id','views','genre_id')->get();
        return $news;
    }

    public function uploadVideo(Request $request)
    {
        $video = '';
       
        //  if ($vid = $request->file('video')) {
        //     $destinationPath = public_path('modules/news/uploads/news/'.'id');
           
        //     $video = 'news'.'_'.Str::random(25).".".$vid->getClientOriginalExtension();

        //     $vid->move($destinationPath, $video); 
        //  }

    //   $imageName = time().'.'.$request->file('video')->extension();  
     
        // $video = Storage::disk('s3')->put('images', $request->file('video'));
        // $video = Storage::disk('s3')->url($video);

        
        $extension =  $request->file('video')->extension();
        $video = Storage::disk('digitalocean')->putFile('uploads', $request->file('video'),'public');
        $evaluatorArray = [52,51,50,49,45]; 
        $authId = Auth::user()->id;
        $evaluateUsersArray = array_diff($evaluatorArray, array($authId));
        $random_key = array_rand($evaluateUsersArray,1);
        $assignTotalVideos = Entities\Evaluation::where('evaluator_id',$evaluatorArray[$random_key])
        ->whereDate('created_at', Carbon::today())->count();
       if($assignTotalVideos == 4){
        $evaluateUsersArray = array_diff($evaluateUsersArray, array($assignTotalVideos));
        $random_key = array_rand($evaluateUsersArray,1);
       }
        if(Auth::user()->id != 45){
            $poster_id = $evaluatorArray[$random_key];
            // $poster_id = 49;
        }
        else {
            $poster_id = 45;
        }

        return response()->json([
            'video' => $video ,
            'duration' => $request['duration'],
            'poster_id' => $poster_id,
        ], 200);
        
    }
    /**  
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(NewsRequests\NewsStoreRequest $request) 
    {
        $video =  $request->input('video');
        $data = $request->all();  
        // $data = $this->data(); 
        // return $request->input('video'); 
        $ticker_duration = Str::of($request->input('ticker_text'))->wordCount();

        if($data['video_type_id'] != 6){
            $news_info['news_type_id'] = $request->input('news_type_id');
            $news_info['ticker_text'] = $request->input('ticker_text');
        }
        else{
            $news_info['news_type_id'] = 1;
            $news_info['ticker_text'] = '';
        }
        $news = $this->model->create([
            'video_type_id' => $request->input('video_type_id'),
            'genre_id' => $request->input('genre_id'),
            'geographical_criteria_id' => $request->input('geographical_criteria_id'),
            'is_celebrity' => $request->input('is_celebrity'),
            'celebrity_genre_id' => $request->input('celebrity_genre_id'),
            'celebrity_name' => $request->input('celebrity_name'),
            'prominence_id' => $request->input('prominence_id'),
            'is_controversy' => $request->input('is_controversy'),
            'human_interest_id' => $request->input('human_interest_id'),
            'news_type_id' =>  $news_info['news_type_id'],
            'ticker_text' =>  $news_info['ticker_text'],
            'ticker_duration' =>  $ticker_duration,
            'news_duration' => $request->input('duration'),
            'video_name' => 'video_name',
            'video_path' => 'video_path',
           "loop_sequence" => 1,
           "news_loop_id" => 1,
           "news_length" => 1,
        ]);
        // return $news;
       
        $news->video_name = "dummy";
        $news->video_path = $request->input('video');
        $coordinates = $request->input('coordinates');
        $news->loop_sequence = 1;
        $news->update();
        // return $news;
        $channel = Entities\Channel::where('genre_id',$request->input('genre_id'))->where('city_id',$request->input('city_id'))->first();
        $channel_has_video = Entities\ChannelHasVideo::create([
            'video_id' => $news->id,
            'channel_id' => $channel->id
        ]);
        $ticker = Entities\Ticker::create([
            'video_id' => $news->id,
            'ticker' => $news->ticker_text,
            'channel_id' => $channel->id

        ]);
        // $address = $this->address($coordinates);
        // return $this->successResponse(
        //     trans('news::news.created'),
        //     new NewsResource($news)
        // );
        $evaluation = Entities\Evaluation::create([
            'evaluator_id' => $request->input('poster_id'),
            'poster_id' => Auth::user()->id,
            'news_id' =>  $news->id,
            'criteria' => "",
        ]);
        $pending_news = Entities\Evaluation::where('evaluator_id',$evaluation->evaluator_id)
        ->leftJoin('news', 'news.id','=',"evaluations.news_id")
        ->where('news.status','pending')
        ->where('news.id',$news->id)
         ->selectRaw('news.*')
         ->leftJoin('users', 'users.id','=','news.user_id')
         ->selectRaw('users.full_name as full_name')
         ->leftJoin('genres', 'genres.id','=','news.genre_id')
         ->selectRaw('genres.name as genre_name')
         ->first();
         $pending_news_decode = json_encode($pending_news);
        //  return $pending_news_decode;

        if(Auth::user()->id ==45){
            $news->status = "approve";
            $news->update();
            $channel_video =  Entities\ChannelHasVideo::where('video_id' , $news->id)
            ->join('channels', 'channels.id','=','channel_has_videos.channel_id')
            ->selectRaw('channels.*')
            ->selectRaw('channel_has_videos.*')->first();
            // $current_date_time = Carbon::now();
            $channel_has_video_loop_exist = Entities\ChannelHasVideoLoop::where('channel_id',$channel_video->channel_id)
            ->first();
            $userInfo = User::where('id', $news->user_id)->first();
            // $channel->id
             $emaildata = array('name'=> $channel->name,
            );
            Mail::send('emails.video-info-mail', $emaildata,  function ($m) use ($userInfo) {
                $m->from('ali.kamran4477696@gmail.com', 'Your Application');
     
                $m->to($userInfo->email, "Kanact Media")->subject('Your Video has been uploaded to');
            });
            $count_ticker = Str::of($news->ticker_text)->wordCount();
            $ticker_division =  $count_ticker/20;
            // return $count_ticker;
    
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
                    'start_time' => Carbon::now()
                ]);
            }
            if($channel_has_video_loop_exist)
            {
                $channel_has_video_loop_exist->video_duration =  $channel_has_video_loop_exist->video_duration + $request->input('duration');
                $channel_has_video_loop_exist->update();
            }
            else{
                $channel_has_video = Entities\ChannelHasVideoLoop::create([
                    'video_duration' => $request->input('duration'),
                    'channel_id' => $channel_video->channel_id,
                    'start_time' => Carbon::now()
                ]);
            }
            broadcast(new EvaluateVideo(json_encode($news),"Your video has been approved", $news->user_id, $channel_video->name ));

        }
        else {
            // return $news->id;
            // $pending_news = (object)[];
             broadcast(new PendingVideo($pending_news_decode, $request->input('poster_id'), $request->input('duration')));
        }
            
        Entities\NewsHasProximity::create([
            'news_id' => $news->id,
            'country' => 'Pakistan',
            'state' => 'Punjab',
            'city' => 'Lahore',
            'coordinates' => $request->input('coordinates'),
        ]);

        if($data['video_type_id'] != 6){

            $news_type = json_decode($data['news_type']); 
            if($news_type[0]->id == 1){
                Entities\NewsHasReport::create([
                    'news_id' => $news->id,
                    'report_type_id' => $news_type[0]->report_type_id,
                    'is_ptc' => $news_type[0]->is_ptc,
                    'is_relevant_footage' => $news_type[0]->is_relevant_footage,
                    'is_on_spot' => $news_type[0]->is_relevant_footage,
                    'is_sot' => $news_type[0]->is_relevant_footage,
                    'is_closing' => $news_type[0]->is_closing,
                    'is_header' => $news_type[0]->is_header,
                    'is_name_strip' => $news_type[0]->is_name_strip,
                    'is_duration_60_to_90' => $news_type[0]->is_duration_60_to_90,
                    'is_ticker' => $news_type[0]->is_ticker,
                ]);
                
            }else if($news_type[0]->id == 2){
                Entities\NewsHasPackage::create([
                    'news_id' => $news->id,
                    'package_type_id' => $news_type[0]->package_type_id,
                    'is_bumper' => $news_type[0]->is_bumper,
                    'is_opening_ptc' => $news_type[0]->is_opening_ptc,
                    'is_relevant_footage' => $news_type[0]->is_relevant_footage,
                    'is_avo' => $news_type[0]->is_avo,
                    'is_diff_version_of_narration' => $news_type[0]->is_diff_version_of_narration,
                    'is_reporter_own_narrative' => $news_type[0]->is_reporter_own_narrative,
                    'is_on_camera_bits' => $news_type[0]->is_on_camera_bits,
                    'is_music' => $news_type[0]->is_music,
                    'is_duration_120_to_180' => $news_type[0]->is_duration_120_to_180,
                    'is_header' => $news_type[0]->is_header,
                    'is_name_strip' => $news_type[0]->is_name_strip,
                    'is_ticker' => $news_type[0]->is_ticker,
                ]);
            }
        }

        return $this->successResponse(
            trans('news::news.created'),
            new NewsResource($news)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(NewsRequests\NewsShowRequest $request, Entities\News $news)
    {
        $newsHasReport = "";
        $newsHasReport = Entities\NewsHasReport::where('news_id', $news->id)->first();
        
        $newsHasPackage = "";
        $newsHasPackage = Entities\NewsHasPackage::where('news_id', $news->id)->first();
        $city = Entities\ChannelHasVideo::where('video_id', $news->id)
        ->join('channels', 'channels.id','=','channel_has_videos.channel_id')
        ->join('cities', 'cities.id','=','channels.city_id')
        ->selectRaw('cities.*')
        ->first();

        return response()->json([
            'news' => new NewsResource($news) ,
            'city' =>  $city,
            "newsHasReport" => $newsHasReport,
            "newsHasPackage" => $newsHasPackage,
        ], 200);
        return new NewsResource($news);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(NewsRequests\NewsUpdateRequest $request, Entities\News $news)
    {
        $news->update($request->input());
        $news->status = "pending";
        $news->update();
        $pending_news = Entities\Evaluation::where('news_id',$news->id)
        ->leftJoin('news', 'news.id','=',"evaluations.news_id")
        // ->where('news.status','pending')
         ->selectRaw('news.*')
         ->leftJoin('users', 'users.id','=','news.user_id')
         ->selectRaw('evaluations.evaluator_id as evaluator_id')
         ->selectRaw('users.full_name as full_name')
         ->leftJoin('genres', 'genres.id','=','news.genre_id')
         ->selectRaw('genres.name as genre_name')
         ->first();
        //  return $pending_news;
         $pending_news_decode = json_encode($pending_news);
        //  return $pending_news_decode;

        if($pending_news->status == "pending"){
            broadcast(new PendingVideo($pending_news_decode, $pending_news->evaluator_id, $news->news_duration));
        }

        return $this->successResponse(
            trans('news::news.updated'),
            new NewsResource($pending_news)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(NewsRequests\NewsDestroyRequest $request, Entities\News $news)
    {
        $news->delete();

        return $this->successResponse(
            trans('news::news.deleted')
        );
    }

    protected function address($coordinates){

        $apiKey = env('GOOGLE_MAPS_KEY');
        $data = file_get_contents("https://maps.google.com/maps/api/geocode/json?latlng=$coordinates&sensor=false&key=".$apiKey);
        $data = json_decode($data); 
        // return $data;
        $add_array  = $data->results;
        $add_array = $add_array[0];
        $add_array = $add_array->address_components;
        $country = "Not found";
        $state = "Not found"; 
        $city = "Not found";

        $address = null;
        foreach ($add_array as $key) {
          if($key->types[0] == 'administrative_area_level_2')
          {
            $city = $key->long_name;
          }
          if($key->types[0] == 'administrative_area_level_1')
          {
            $state = $key->long_name;
          }
          if($key->types[0] == 'country')
          {
            $country = $key->long_name;
          }

          $address[] = [
            'country' => $country,
            'state' => $state,
            'city' => $city,
          ];

          return $address;

        }
    }

    private function data()
    {
        $data = [
            'video' => 'abc',
            'video_type_id' => 1,
            'genre_id' => 1,
            'geographical_criteria_id' => 1,
            'is_celebrity' => 1,
            'celebrity_genre_id' => 1,
            'celebrity_name' => 'Ali',
            'prominence_id' => 1,
            'is_controversy' => 0,
            'human_interest_id' => 1,
            'coordinates' => '31.459322,74.363802',
            'news_type_id' => 1, #1/2

            'news_type' => [
                '' => 1,
                'is_ptc' => report_type_id1,
                'is_relevant_footage' => 1,
                'is_on_spot' => 1,
                'is_sot' => 1,
                'is_closing' => 1,
                'is_header' => 1,
                'is_name_strip' => 1,
                'is_duration_60_to_90' => 1,
                'is_ticker' => 1,
            ],

            'news_type' => [
                'package_type_id' => 1,
                'is_bumper' => 1,
                'is_opening_ptc' => 1,
                'is_relevant_footage' => 1,
                'is_avo' => 1,
                'is_diff_version_of_narration' => 1,
                'is_reporter_own_narrative' => 1,
                'is_on_camera_bits' => 1,
                'is_music' => 1,
                'is_duration_120_to_180' => 1,
                'is_header' => 1,
                'is_name_strip' => 1,
                'is_ticker' => 1,
            ],

        ];

        return $data;
    }
   /**
     * Get pending videos for report
     */
    public function getReporterPendingVideos()
    {
        // $news = $this->model->where('user_id', Auth::user()->id)->where('status', 'pending')->get();
        // $news = $this->model->orderBy('id', 'DESC')  ->leftJoin('users', 'users.id','=','news.user_id')
        // ->selectRaw('news.*')
        // ->selectRaw('users.full_name as full_name')
        // ->where('news.status','=', 'pending')
        // ->leftJoin('genres', 'genres.id','=','news.genre_id')
        // ->selectRaw('genres.name as genre_name')
        // ->get();

        $news = Entities\Evaluation::where('evaluator_id',Auth::user()->id)
                    ->leftJoin('news', 'news.id','=','evaluations.news_id')
                    ->where('news.status','pending')
                     ->selectRaw('news.*')
                     ->leftJoin('users', 'users.id','=','news.user_id')
                     ->selectRaw('users.full_name as full_name')
                     ->leftJoin('genres', 'genres.id','=','news.genre_id')
                     ->selectRaw('genres.name as genre_name')
                     ->orderBy('news.id', 'DESC')
                     ->get();
        // create([
        //     'evaluator_id' => $request->evaluator_id,
        //     'poster_id' => $request->poster_id,
        //     'news_id' =>  $news->id,
        //     'criteria' => $request->criteria,
        // ]);
        return $this->successResponse(
            'Get Reporter Pending Video success',
            new NewsResource($news)
        );
    }
     /**
     * Get single pending video for evaluator
     */
    public function getReporterSinglePendingVideo($id)
    {
        $newsHasReport = "";
        $newsHasReport = Entities\NewsHasReport::where('news_id', $id)->first();
        
        $newsHasPackage = "";
        $newsHasPackage = Entities\NewsHasPackage::where('news_id', $id)->first();

        $news = "";
        $news = Entities\News:: where('news.id',$id)
        ->join('channel_has_videos', 'channel_has_videos.video_id','=','news.id')
        ->join('channels', 'channels.id','=','channel_has_videos.channel_id')
        ->join('cities', 'cities.id','=','channels.city_id')
        ->selectRaw('cities.name as city_name')
        ->leftJoin('users', 'users.id','=','news.user_id')
        ->selectRaw('news.*')
        ->selectRaw('users.full_name as full_name')
        ->where('news.status',"pending")
       
                     ->leftJoin('genres', 'genres.id','=','news.genre_id')
                     ->selectRaw('genres.name as genre_name')

                     ->leftJoin('human_interests', 'human_interests.id','=','news.human_interest_id')
                     ->selectRaw('human_interests.name as human_interest_name')

                     ->leftJoin('prominences', 'prominences.id','=','news.prominence_id')
                     ->selectRaw('prominences.name as prominence_name')

                     ->leftJoin('video_types', 'video_types.id','=','news.video_type_id')
                     ->selectRaw('video_types.name as video_type_name')

                     ->leftJoin('news_types', 'news_types.id','=','news.news_type_id')
                     ->selectRaw(' news_types.name as  news_type_name')

                     ->leftJoin('geographical_criterias', 'geographical_criterias.id','=','news.geographical_criteria_id')
                     ->selectRaw('geographical_criterias.name as geographical_criteria_name')
                     ->first();
       
        return response()->json([
            'news' => $news ,
            'duration' =>  $news->news_duration,
            "newsHasReport" => $newsHasReport,
            "newsHasPackage" => $newsHasPackage,
        ], 200);
    }

     /**
     * Get Reprter Modify Detail 
     */
    public function getReporterModifyVideo ($id)
    {
        $evaluation = Entities\Evaluation::where('id',$id)->first();
        return $evaluation;
    }

     /**
     * Get Poster Modify videos 
     */
    public function getPosterModifyVideos()
    {
    
        $news = Entities\Evaluation::where('poster_id',Auth::user()->id)
                    ->where('criteria','!=','')
                    ->leftJoin('news', 'news.id','=','evaluations.news_id')
                    ->where('news.status','!=','pending')
                    ->selectRaw('evaluations.criteria as criteria')
                    ->selectRaw('evaluations.id as evaluation_id')
                     ->selectRaw('news.*')
                     ->leftJoin('users', 'users.id','=','news.user_id')
                     ->selectRaw('users.full_name as full_name')
                     ->leftJoin('genres', 'genres.id','=','news.genre_id')
                     ->selectRaw('genres.name as genre_name')
                     ->orderBy('news.id', 'DESC')
                     ->get();
      
        return $this->successResponse(
            'Get Reporter Pending Video success',
            new NewsResource($news)
        );
    }

    function updateTotalViews($id){
        $videoCount = Entities\News::
        where('id',$id)->first();
        $videoCount->views += 1;
        $videoCount->update();
        return $videoCount;
    }

}






