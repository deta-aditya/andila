<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Show the index agent page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Only allow admin
        if (! session('weblogin')->isAdmin()) {
            abort(403);
        }

        return view('inner.user.index');
    }

    /**
     * Process the session before going to index page.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preindex(Request $request)
    {
        if ($request->has('post')) {
        	session()->flash('success', 'Pengguna "'. User::find($request->get('post'))->email .'" telah berhasil disimpan!');
        }

        if ($request->has('put')) {
        	session()->flash('success', 'Pengguna "'. User::find($request->get('put'))->email .'" telah berhasil diubah!');
        }

        if (session('weblogin')->isAdmin()) {
            return redirect()->route('web.users.index');
        } else {
            return redirect()->route('web.'. strtolower(session('weblogin')->handling . 's') .'.show', session('weblogin')->handleable->id);
        }
    }

    /**
     * Show the create user page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only allow admin
        if (! session('weblogin')->isAdmin()) {
            abort(403);
        }

        return view('inner.user.create');
    }

    /**
     * Show the edit user page.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Only allow admin and user with the same id
        if (! session('weblogin')->isAdmin() && session('weblogin')->id !== $user->id) {
            abort(403);
        }

        return view('inner.user.edit', ['user_edit' => $user]);
    }
}
