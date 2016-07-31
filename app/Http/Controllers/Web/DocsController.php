<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DocsController extends Controller
{
    /**
     * Show index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('docs.index', [
            'bodyClass' => 'bs19-body-welcome',
        ]);
    }

    /**
     * Show any page.
     *
     * @param  string  $category
     * @param  string|null  $page
     * @return \Illuminate\Http\Response
     */
    public function read($category, $page)
    {
        $category = $category === 'administrator' ? 'admin' : $category;

        // if (
        //     ($category !== 'api' && ! session()->has('weblogin')) ||
        //     ($category === 'api' && session()->has('weblogin')) ||
        //     ($category === 'admin' && ! session('weblogin')->isAdmin()) ||
        //     ($category === 'agent' && ! session('weblogin')->isAgent()) ||
        //     ($category === 'subagent' && ! session('weblogin')->isSubagent())
        // ) {
        //     abort(403);
        // }

        $view_name = 'docs.' . $category . '.' . $page;

        if (view()->exists($view_name)) {
            return view($view_name, [
                'bodyClass' => 'bs19-body-welcome',
                'category' => $category,
                'page' => $page,
            ]);    
        } else {
            abort(404);
        }
    }    
}
