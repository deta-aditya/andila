<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\IndonesiaRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndonesiaController extends Controller
{
	/**
     * The indonesia repository instance.
     *
     * @var IndonesiaRepository
     */
    protected $indonesias;

    /**
     * Create a new controller instance.
     *
     * @param  IndonesiaRepository  $indonesias
     * @return void
     */
    public function __construct(IndonesiaRepository $indonesias)
    {
        $this->indonesias = $indonesias;
    }

    /**
     * Display a listing of the provinces resource.
     *
     $ @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function provinces(Request $request)
    {
        $valid = $this->indonesias->valid($request->all(), 'Province');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->indonesias->provinces($request->all()), 200);
    }

    /**
     * Display a listing of the regencies resource.
     *
     $ @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function regencies(Request $request)
    {
        $valid = $this->indonesias->valid($request->all(), 'Regency');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->indonesias->regencies($request->all()), 200);
    }

    /**
     * Display a listing of the districts resource.
     *
     $ @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function districts(Request $request)
    {
        $valid = $this->indonesias->valid($request->all(), 'District');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->indonesias->districts($request->all()), 200);
    }

    /**
     * Display a listing of the subdistricts resource.
     *
     $ @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subdistricts(Request $request)
    {
        $valid = $this->indonesias->valid($request->all(), 'Subdistrict');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->indonesias->subdistricts($request->all()), 200);
    }
}
