<?php

namespace App\Http\Controllers\Agen;

use Helper;
use Illuminate\Http\Request;
use App\Repositories\DailyDistributionRepository;
use App\Repositories\PangkalanRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DailyDistributionController extends Controller
{
    
    protected $dailyDistributions;
    protected $pangkalans;

    public function __construct(DailyDistributionRepository $dailyDistributions, PangkalanRepository $pangkalans)
    {
        $this->dailyDistributions = $dailyDistributions;
        $this->pangkalans = $pangkalans;
    }

    public function index()
    {
    	// return view('admin.dashboard');
    }

    public function thisMonth()
    {
    	
    }

    public function month($month)
    {

    }

    public function thisWeek()
    {

    }

    public function week($week)
    {

    }

    public function today()
    {

    }

    public function day(Request $request, $day)
    {
        $data = [
            'db' => [
                'pangkalans' => $this->pangkalans->me(),
                'dists' => $this->dailyDistributions->index($request, $day, 'pangkalan')
            ],
            'range' => $this->range($day)
        ];

        if ($request->has('pangkalan')) {
            $data['pangkalan'] = $request->input('pangkalan');
        }

        if ($request->day) {
            $dates = $this->dailyDistributions->extractDay($day);
            $data['date_from'] = $dates[0];

            if (count($dates) > 1) {
                $data['date_to'] = $dates[1];
            }
        }

        return view('agen.daily.index')->with($data);
    }

    public function report($date)
    {
    	// return view('admin.dashboard');
    }

    private function range($date)
    {
        $dates = $this->dailyDistributions->extractDay($date);
        $dateNew = Helper::indonesianDate($dates[0], 'j F Y', '');

        if (count($dates) > 1) {
            $dateNew .= ' - ' . Helper::indonesianDate($dates[1], 'j F Y', '');
        }

        return 'periode '. $dateNew;
    }
}
