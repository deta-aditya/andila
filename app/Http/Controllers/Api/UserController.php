<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\MessageRepository;
use App\Models\User;

class UserController extends Controller
{
    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * The message repository instance.
     *
     * @var MessageRepository
     */
    protected $messages;

    /**
     * Create a new controller instance.
     *
     * @param  User  $user
     * @param  UserRepository  $users
     * @param  MessageRepository  $messages
     * @return void
     */
    public function __construct(
        UserRepository $users,
        MessageRepository $messages)
    {
        $this->users = $users;
        $this->messages = $messages;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->cannot('user-index')) {
            return response()->json(['errors' => 'The accessing user is not authorized to access the current action.'], 403);
        }

        $valid = $this->users->valid($request->all(), 'Index');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->users->index($request->all()), 200);
    }

    /**
     * Store a newly created resource in storage as administrator.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function admin(Request $request)
    {
        if (auth()->user()->cannot('user-admin')) {
            return response()->json(['errors' => 'The accessing user is not authorized to access the current action.'], 403);
        }

        $valid = $this->users->valid($request->all(), 'Admin');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->users->admin($request->all()), 200);
    }

    /**
     * Handle a stateless authentication attempt.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $valid = $this->users->valid($request->all(), 'Auth', [
            'email' => $request->input('email', null),
            'password' => $request->input('password', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->users->getAccessToken(User::where('email', $request->email)->first()), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if (auth()->user()->cannot('user-show', $user)) {
            return response()->json(['errors' => 'The accessing user is not authorized to access the current action.'], 403);
        }

        $valid = $this->users->valid($request->all(), 'Show');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->users->show($user, $request->all()), 200);
    }

    /**
     * Display the inbox of the specified resource.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function inbox(Request $request, User $user)
    {
        if (auth()->user()->cannot('user-inbox', $user)) {
            return response()->json(['errors' => 'The accessing user is not authorized to access the current action.'], 403);
        }

        $valid = $this->messages->valid($request->all(), 'Inbox');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->inbox($user, $request->all()), 200);
    }

    /**
     * Display the outbox of the specified resource.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function outbox(Request $request, User $user)
    {
        if (auth()->user()->cannot('user-outbox', $user)) {
            return response()->json(['errors' => 'The accessing user is not authorized to access the current action.'], 403);
        }

        $valid = $this->messages->valid($request->all(), 'Outbox');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->outbox($user, $request->all()), 200);
    }

    /**
     * Display the draftbox of the specified resource.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function draftbox(Request $request, User $user)
    {
        if (auth()->user()->cannot('user-draftbox', $user)) {
            return response()->json(['errors' => 'The accessing user is not authorized to access the current action.'], 403);
        }

        $valid = $this->messages->valid($request->all(), 'Draftbox');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->messages->draftbox($user, $request->all()), 200);
    }

    /**
     * Display the attachments of the specified resource.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function attachments(Request $request, User $user)
    {
        //
    }

    /**
     * Display the activities of the specified resource.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function activities(Request $request, User $user)
    {
        //
    }

    /**
     * Display the notifications of the specified resource.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function notifications(Request $request, User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->cannot('user-update', $user)) {
            return response()->json(['errors' => 'The accessing user is not authorized to access the current action.'], 403);
        }

        $valid = $this->users->valid($request->all(), 'Update', [
            'email' => $user->email,
            'password' => $request->input('password_old', null)
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->users->update($user, $request->all()), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (auth()->user()->cannot('user-destroy', $user)) {
            return response()->json(['errors' => 'The accessing user is not authorized to access the current action.'], 403);
        }

        return response()->json($this->users->destroy($user), 200);
    }
}
