<?php

namespace Modules\News\Http\Controllers;

// use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;
use Modules\News\Entities;
use Auth;
use App\Events\EvaluateTickerEvent;
use Mail;
use App\User;
use Modules\Core\Http\Controllers\Controller;
use App\Lib\PusherFactory;

class TickerController extends Controller
{
  
    public function index()
    {
        $tickers = Entities\Ticker::all();
        return  $tickers;
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
        $ticker = Entities\Ticker::create([
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'start_date' =>  $request->input('start_date'),
            'end_date' =>  $request->input('end_date'),
            'ticker' =>  $request->input('ticker'),
            'channel_id' =>  $request->input('channel_id'),
            'status' =>  "pending",
        ]);
        $evaluateticker = Entities\EvaluateTicker::create([
            'reason' => '',
            'evaluator_id' => 45,
            'poster_id' =>   Auth::user()->id,
            'ticker_id' =>   $ticker->id,
            
        ]);
        $userInfo = User::where('id', 45)->first();
        $authId = Auth::user()->id;
        $emaildata = array('name'=> 'mobeen',
        );
        
        broadcast(new EvaluateTickerEvent(json_encode($ticker),"Your ticker is in pending", 45, $authId, $ticker->status ));

        return $ticker;
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
     * Update the specified resource in storage.
     * @param int $userid
     * @return pending video
     */
    
    public function pendingTicker()
    {
        $pendingticker = Entities\EvaluateTicker::where('evaluator_id', Auth::user()->id)
        ->join('tickers', 'tickers.id','=','evaluate_tickers.ticker_id')
        ->where('tickers.status','pending')
        ->join('users', 'users.id','=','evaluate_tickers.poster_id')
        ->selectRaw('tickers.*')
        ->selectRaw('users.full_name as poster_name')
        ->get();
        return $pendingticker;
    }
    public function evaluateTicker()
    {
        $pendingticker = Entities\EvaluateTicker::where('poster_id', Auth::user()->id)
        ->join('tickers', 'tickers.id','=','evaluate_tickers.ticker_id')
        ->where('tickers.status', '!=' ,'pending')
        ->selectRaw('tickers.*')
        ->get();
        return $pendingticker;
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
