<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\News\Entities;
use Carbon\Carbon;

use Auth;

class ChatBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $chatBox = Entities\ChatBox::where("receiver_id", Auth::user()->id)->get();
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
        $chatBox = "";
        $chatBox = Entities\ChatBox::where("sender_id", Auth::user()->id)
        ->where("receiver_id", $request->receiver_id)->first();

        if($chatBox)
            $chatBox = Entities\ChatBox::where("sender_id",$request->sender_id )
            ->where("receiver_id", Auth::user()->id)->first();
        if(!$chatBox)
            $chatBox = Entities\ChatBox::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'evaluation_id' =>  $request->evaluation_id,
            ]);
        $chatRoom = Entities\ChatRoom::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' =>  $request->message,
            'chat_room_id' =>  $chatRoom->id,
        ]);

        return $chatRoom;
        
       
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $messages = [];
        $chatRoom = "";

        $chatRoom = Entities\ChatRoom::where("evaluation_id", $id)->first();
        if($chatRoom)
            $messages = Entities\ChatBox::where("chat_room_id", $chatRoom->id)->get();
       
        return $messages;
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
