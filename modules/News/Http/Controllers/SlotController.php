<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\News\Entities;
use Carbon\Carbon;
use App\Events\LatestSlotEvent;
class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $slots = Entities\Slot::all();
        return $slots;
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
        $slot = Entities\Slot::create([
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'start_date' =>  $request->input('start_date'),
            'end_date' =>  $request->input('end_date'),
            'slot_type_id' =>  $request->input('slot_type_id'),
            'archive_id' =>  $request->input('archive_id'),
            'duration' =>  $request->input('duration'),
            'channel_id' =>  $request->input('channel_id'),
        ]);

        $channel_has_video_loop_exist = Entities\ChannelHasVideoLoop::where('channel_id',$request->input('channel_id'))
        ->first();
        if($channel_has_video_loop_exist)
        {
            $channel_has_video_loop_exist->video_duration =  $channel_has_video_loop_exist->video_duration + $request->input('duration');
            $channel_has_video_loop_exist->update();
        }
        else{
                 Entities\ChannelHasVideoLoop::create([
                'video_duration' => $request->input('duration'),
                'channel_id' => $request->input('channel_id'),
                'start_time' => Carbon::now()
            ]);
        }
        $channel_slot = Entities\Slot::where('channel_id',$slot->channel_id)
        ->join('channels', 'channels.id','=','slots.channel_id')
        ->where('slots.id',$slot->id)
        ->join('slot_types', 'slot_types.id','=','slots.slot_type_id')
        ->join('archives', 'archives.id','=','slots.archive_id')
        ->selectRaw('slots.*')
        ->selectRaw('archives.video_path as video_path')
        ->whereDate('slots.start_date', Carbon::today())
        ->whereTime('slots.start_time', '>=',  \Carbon\Carbon::now()->timezone('Asia/Karachi')->format('H:i:s'))
        ->first();
        if(!empty($channel_slot)){
            broadcast(new LatestSlotEvent(json_encode($channel_slot),$slot->channel_id ));
        }
        return $slot;
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
        $slot = Entities\Slot::where('id',$id)->first();
        $slot->update($request->input());
        $channel_slot = Entities\Slot::where('channel_id',$slot->channel_id)
        ->join('channels', 'channels.id','=','slots.channel_id')
        ->where('slots.id',$slot->id)
        ->join('slot_types', 'slot_types.id','=','slots.slot_type_id')
        ->join('archives', 'archives.id','=','slots.archive_id')
        ->selectRaw('slots.*')
        ->selectRaw('archives.video_path as video_path')
        ->whereDate('slots.start_date', Carbon::today())
        ->whereTime('slots.start_time', '>=',  \Carbon\Carbon::now()->timezone('Asia/Karachi')->format('H:i:s'))
        ->first();
        if(!empty($channel_slot)){
            broadcast(new LatestSlotEvent(json_encode($channel_slot),$slot->channel_id ));
        }
        return $channel_slot;
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
