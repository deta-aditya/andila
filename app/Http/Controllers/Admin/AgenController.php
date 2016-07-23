<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\AgenRepository;
use App\Repositories\StationRepository;
use App\Repositories\ProvinceRepository;
use App\Agen;
use Helper;

class AgenController extends Controller
{

    protected $agens;
    protected $stations;
    protected $provinces;

    public function __construct(AgenRepository $agens, StationRepository $stations, ProvinceRepository $provinces)
    {
        $this->agens = $agens;
        $this->stations = $stations;
        $this->provinces = $provinces;
    }

    public function index()
    {
    	$data = [
            'db' => [
                'agens' => $this->agens->forStandardIndex()
            ]
        ];

        return view('admin.agen.index')->with($data);

    }

    public function create(Request $request)
    {
        $data = [
            'db' => [
                'stations' => $this->stations->all(),
                'provinces' => $this->provinces->all()
            ]
        ];

        if ($request->has('station')) {
            $data['station'] = $request->input('station');
        }

        return view('admin.agen.create')->with($data);
    }

    public function show(Agen $agen)
    {
        $agen = $this->agens->withPangkalanWeekly($agen);

    	$data = [
            'db' => ['agen' => $agen]
        ];

        return view('admin.agen.show')->with($data);
    }

    public function edit(Agen $agen)
    {
    	// return view('admin.dashboard');
    }
}
