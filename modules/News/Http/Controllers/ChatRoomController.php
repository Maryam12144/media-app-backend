<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\News\Entities;
use App\Events\MessageEvent;
use Carbon\Carbon;
use Auth;

class ChatRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $chatBox = Entities\ChatBox::join('chat_rooms','chat_rooms.id','=','chat_boxes.chat_room_id')
        
        ->join('users as receiverUser','receiverUser.id','=','chat_rooms.receiver_id')
        ->selectRaw('receiverUser.full_name as receiver_name')
        ->join('users','users.id','=','chat_rooms.sender_id')
        ->selectRaw('users.full_name as sender_name')
        ->selectRaw('chat_rooms.evaluation_id as evaluation_id')
        ->selectRaw('chat_rooms.created_at as created_at')
        ->selectRaw('chat_rooms.id as id')
        ->selectRaw('chat_rooms.is_read as is_read')
        
        ->join('evaluations','evaluations.id','=','chat_rooms.evaluation_id')
        ->selectRaw('evaluations.criteria as status')
        // ->groupBy('chat_boxes.id')
        ->distinct()
        ->get();
        return $chatBox;
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
        // $chatBox = Entities\ChatRoom::create([
        //             'sender_id' => $request->sender_id,
        //             'receiver_id' => $request->receiver_id,
        //             'evaluation_id' =>  $request->evaluation_id,
        //         ]);
        $chatRoom = "";
        $chatRoom = Entities\ChatRoom::where('evaluation_id',$request->evaluation_id)
        ->where("sender_id", Auth::user()->id)
        ->orWhere("receiver_id", Auth::user()->id)
        ->first();
        if(!$chatRoom || $chatRoom == '')
        {
            $chatRoom = Entities\ChatRoom::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'evaluation_id' =>  $request->evaluation_id,
            ]);
        }
       
        $chatBox = Entities\ChatBox::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' =>  $request->message,
            'chat_room_id' =>  $chatRoom->id,
        ]);
        broadcast(new MessageEvent($request->evaluation_id, $chatBox->sender_id, $chatBox->receiver_id, $chatRoom->id, $chatBox));

        return $chatBox;
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $chatRoom = Entities\Evaluation::where("id", $id)->first();
        return $chatRoom;
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
 /**
     * Remove the specified resource from storage.
     * @return Read Notification Count
     */
    public function getTotalNotification()
    {
        $chatRoom = Entities\ChatRoom::
        where('is_read',0)
        ->where('sender_id',Auth::user()->id)
        ->orWhere('receiver_id',Auth::user()->id)->count();
        return $chatRoom;
    }
    public function updateTotalNotification($id)
    {
        $chatRoom = Entities\ChatRoom::
        where('id',$id)->first();
        $chatRoom->is_read = 1;
        $chatRoom->update();

        // $totalNotification = Entities\ChatRoom::
        // where('is_read',0)
        // ->where('sender_id',Auth::user()->id)
        // ->orWhere('receiver_id',Auth::user()->id)->count();
        return $chatRoom->is_read;
    }
}
