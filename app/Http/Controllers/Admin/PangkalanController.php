<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\PangkalanRepository;
use App\Repositories\AgenRepository;
use App\Repositories\ProvinceRepository;
use App\Pangkalan;

class PangkalanController extends Controller
{

    protected $pangkalans;
    protected $agens;
    protected $provinces;

    public function __construct(PangkalanRepository $pangkalans, AgenRepository $agens, ProvinceRepository $provinces)
    {
        $this->pangkalans = $pangkalans;
        $this->agens = $agens;
        $this->provinces = $provinces;
    }

    public function index()
    {
    	$data = [
            'db' => [
                'pangkalans' => $this->pangkalans->forStandardIndex()
            ]
        ];
        
        return view('admin.pangkalan.index')->with($data);
    }

    public function create(Request $request)
    {
    	$data = [
            'db' => [
                'agens' => $this->agens->all(),
                'provinces' => $this->provinces->all()
            ]
        ];

        if ($request->has('agen')) {
            $data['agen'] = $request->input('agen');
        }

        return view('admin.pangkalan.create')->with($data);
    }

    public function show(Pangkalan $pangkalan)
    {
    	// return view('admin.dashboard');
    }

    public function edit(Pangkalan $pangkalan)
    {
    	// return view('admin.dashboard');
    }
}
