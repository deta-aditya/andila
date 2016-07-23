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

        return redirect()->route('web.users.index');
    }

    /**
     * Show the create user page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        return view('inner.user.edit', ['user_edit' => $user]);
    }
}
