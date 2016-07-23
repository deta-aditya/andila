<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use App\Repositories\AttachmentRepository;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * The message repository instance.
     *
     * @var MessageRepository
     */
    protected $messages;

	/**
     * The attachment repository instance.
     *
     * @var AttachmentRepository
     */
    protected $attachments;

    /**
     * Create a new controller instance.
     *
     * @param  MessageRepository  $messages
     * @return void
     */
    public function __construct(
        MessageRepository $messages,
        AttachmentRepository $attachments)
    {
        $this->messages = $messages;
        $this->attachments = $attachments;
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Message $message)
    {
        $valid = $this->messages->valid($request->all(), 'Show');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->show($message, $request->all()), 200);
    }

    /**
     * Display the attachments of specified resource.
     *
     * @param  Request  $request
     * @param  Message  $message
     * @return \Illuminate\Http\Response
     */
    public function attachments(Request $request, Message $message)
    {
        $params = array_add($request->all(), 'message', $message->id);
    	$valid = $this->attachments->valid($params, 'Index');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->attachments->index($params), 200);
    }

    /**
     * Bulk store multiple attachments in the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Message  $message
     * @return \Illuminate\Http\Response
     */
    public function multipleAttachment(Request $request, Message $message)
    {
        //
    }

    /**
     * Store and send a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function singleSend(Request $request)
    {
    	$valid = $this->messages->valid($request->all(), 'SingleSend');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->single($request->all(), true), 201);
    }

    /**
     * Store a newly created resource in storage as a draft.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function singleDraft(Request $request)
    {
    	$valid = $this->messages->valid($request->all(), 'SingleDraft');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->single($request->all(), false), 201);
    }

    /**
     * Send the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, Message $message)
    {
    	$valid = $this->messages->valid($request->all(), 'Send', [
    		'message' => $message,
    	]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->send($message, $request->all()), 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request, Message $message)
    {
    	$valid = $this->messages->valid($request->all(), 'Read', [
    		'message' => $message,
    	]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->read($message), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        $valid = $this->messages->valid($request->all(), 'Update', [
            'message' => $message,
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->update($message, $request->all()), 200);
    }
}
