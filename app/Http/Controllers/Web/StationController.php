<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\StationRepository;
use App\Http\Controllers\Controller;
use App\Models\Station;

class StationController extends Controller
{
	/**
     * The station repository instance.
     *
     * @var StationRepository
     */
    protected $stations;

    /**
     * Create a new controller instance.
     *
     * @param  StationRepository  $stations
     * @return void
     */
    public function __construct(StationRepository $stations)
    {
        $this->stations = $stations;
    }

    /**
     * Show the index station page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inner.station.index');
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
        	session()->flash('success', 'Stasiun "'. Station::find($request->get('post'))->name .'" telah berhasil disimpan!');
        }

        if ($request->has('put')) {
            session()->flash('success', 'Stasiun "'. Station::find($request->get('put'))->name .'" telah berhasil diubah!');
        }

        if ($request->has('delete')) {
        	session()->flash('success', 'Stasiun "'. $request->get('delete') .'" telah berhasil dihapus!');
        }

        return redirect()->route('web.stations.index');
    }

    /**
     * Show the detail station page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Station $station)
    {
        $data = [ 'original' => $this->stations->show($station, [
            'agents' => 1,
        ])];

        $data['station'] = $data['original']['model'];
        $data['uri'] = url('api/v0/stations/' . $data['station']['id']);

        return view('inner.station.show', $data);
    }

    /**
     * Show the create station page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('inner.station.create');
    }

    /**
     * Show the edit station page.
     *
     * @param  Station  $station
     * @return \Illuminate\Http\Response
     */
    public function edit(Station $station)
    {
        return view('inner.station.edit', ['station' => $station]);
    }
}
