<?php

namespace App\Http\Controllers\Admin;

use Helper;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\StationRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\MonthlyDistributionRepository;
use App\Repositories\AgenRepository;
use App\Station;

class StationController extends Controller
{

    protected $stations;
    protected $provinces;
    protected $monthlies;
    protected $agens;

    public function __construct(StationRepository $stations, ProvinceRepository $provinces, AgenRepository $agens, MonthlyDistributionRepository $monthlies)
    {
        $this->stations = $stations;
        $this->provinces = $provinces;
        $this->monthlies = $monthlies;
    	$this->agens = $agens;
    }

    public function index()
    {
        $data = [
            'db' => [
                'stations' => $this->stations->forStandardIndex()
            ]
        ];

    	return view('admin.station.index')->with($data);
    }

    public function create()
    {
        $data = [
            'db' => [
                'provinces' => $this->provinces->all()
            ]
        ];

        return view('admin.station.create')->with($data);
    }

    public function show(Station $station)
    {
        $station = $this->stations->withAgenMonthly($station);

        $data = [
            'db' => ['station' => $station]
        ];

    	return view('admin.station.show')->with($data);        
    }

    public function edit(Station $station)
    {
    	// return view('admin.dashboard');
    }
}
