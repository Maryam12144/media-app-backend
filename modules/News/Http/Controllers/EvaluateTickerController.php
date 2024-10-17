<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use App\Events\EvaluateTickerEvent;
use Modules\News\Entities;

class EvaluateTickerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $evaluatetickers = Entities\EvaluateTicker::all();
        return  $evaluatetickers;
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
    public function store(Request $request, $id)
    {
        $evaluateticker = Entities\EvaluateTicker::where('id',$id)->first();
        $evaluateticker->update($request->input());

        $ticker = Entities\Ticker::where('id',$evaluateticker->ticker_id)->first();
        $ticker->update($request->input());

        $authId = Auth::user()->id;
        
        broadcast(new EvaluateTickerEvent(json_encode($ticker),"Your ticker is ".$ticker->status, 
        '', '', $ticker->status ));

        return $evaluateticker;
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
