<?php

namespace App\Repositories;

use Helper;
use Validator;
use Carbon\Carbon;
use App\Events\MonthlyDistributionWasAllocated;
use App\MonthlyDistribution;
use App\DailyDistribution;
use App\Station;
use App\Agen;

class MonthlyDistributionRepository
{

    public function allWithQueries($inputs)
    {
        $query = MonthlyDistribution::query()
                    ->orderBy('id', 'asc');

        if (isset($inputs['station'])) {
            $query->where('station_id', '=', $inputs['station'])->orderBy('agen_id', 'asc');
        }

        if (isset($inputs['planned_month'])) {
            $when = Carbon::now()->format('Y-m');

            if ($inputs['planned_month'] != 'this') {
                $when = Carbon::parse($inputs['planned_month'])->format('Y-m');
            }

            $query->whereRaw('DATE_FORMAT(`date_planned`, "%Y-%m") = "'. $when .'"');
        }

        return $query->get();
    }

    public function seed(Station $station, Agen $agen, $months)
    {
        $range = Helper::getMonthsArray($months);
        $monthlies = [];

        foreach ($range as $date) {
            $monthlies[] = MonthlyDistribution::create([
                'station_id' => $station->id,
                'agen_id' => $agen->id,
                'date_planned' => $date
            ]);
        }

        return collect($monthlies);
    }
    
    public function batchPlan($request)
    {
        $station = Station::find($request->input('station_id'));
        $thisMonth = Carbon::now()->format('Y-m');
        $datePlans = $request->input('date_planned');
        $monthlies = [];

        foreach ($datePlans as $id => $date) {
            
            $agen = Agen::find($id);

            $monthly = MonthlyDistribution::query()
                ->where('agen_id', $agen->id)
                ->where('station_id', $station->id)
                ->whereRaw('DATE_FORMAT(`date_planned`, "%Y-%m") = "'. $thisMonth. '"')
                ->first();

            if(! $monthly) {
                continue;
            }

            $monthly->allocation = $agen->quota_monthly;
            $monthly->date_planned = $date;
            $monthly->save();

            $monthlies[] = $monthly;

            event(new MonthlyDistributionWasAllocated($agen, $monthly));
        }

        return $monthlies;
    }

    public function validateBatch($inputs)
    {
        return Validator::make($inputs, [
            'date_planned.*' => 'date'
        ]);
    }
}
