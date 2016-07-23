<?php

namespace App\Repositories;

use Helper;
use Sentinel;
use Validator;
use Carbon\Carbon;
use App\DailyDistribution;
use App\MonthlyDistribution;
use App\Station;
use App\Agen;
use App\Pangkalan;

class DailyDistributionRepository
{

	public function allWithQueries($inputs)
	{
		$query = DailyDistribution::query()
					->orderBy('id', 'asc');

		if (isset($inputs['agen'])) {
			$query->where('agen_id', '=', $inputs['agen'])->orderBy('pangkalan_id', 'asc');
		}

		if (isset($inputs['planned_day'])) {
			$when = date('Y-m-d');

			if ($inputs['planned_day'] != 'this') {
				$when = date('Y-m-d', strtotime($inputs['planned_day']));
			}

			$query->where('date_planned', '=', $when);
		}

		return $query->get();
	}
	
	public function index($request, $date, $group = null)
	{
		$dates = $this->extractDay($date);

		$query = DailyDistribution::with('agen', 'pangkalan')
			->orderBy('id', 'asc');

		if (count($dates) > 1) {
			$query->whereBetween('date_planned', $dates);
		} else {
			$query->where('date_planned', '=', $dates[0]);
		}

		if ($request->has('agen') && Sentinel::inRole('admin')) {
			$query->where('agen_id', '=', $request->input('agen'));
		}

		if ($request->has('pangkalan')) {
			
			if (Sentinel::inRole('agen')) {
				$user = Sentinel::getUser();
				$pangkalan = Pangkalan::find($request->input('pangkalan'));

				if ($pangkalan->agen_id == $user->workplace_id) {
					$query->where('pangkalan_id', '=', $request->input('pangkalan'));
				}

			} elseif (Sentinel::inRole('admin')) {
				$query->where('pangkalan_id', '=', $request->input('pangkalan'));
			}
		}

		$result = $query->get();

		if (! is_null($group)) {
			$result->groupBy($group .'_id');
		}

		return $result;
	}

	public function seed($pangkalans, Agen $agen, MonthlyDistribution $monthly)
	{
		$range = Helper::getDatesArray($monthly->date_planned, 30);
		$dailies = [];

		foreach ($pangkalans as $pangkalan) {
			foreach ($range as $date) {
				$dailies[] = DailyDistribution::create([
					'pangkalan_id' => $pangkalan->id,
					'agen_id' => $agen->id,
					'date_planned' => $date
				]);
			}
		}

		return collect($dailies);
		
	}

	public function extractDay($date)
	{
		switch ($date) {

			case 'today':
				$dates = [ Carbon::now()->toDateString() ];
				break;

			case 'this-week':
				$dates = [ Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString() ];
				break;

			case 'this-month':
				$dates = [ Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString() ];
				break;

			default:
				$dates = explode('_', $day);
				break;
				
		}

		return $dates;
	}

	/*public function batchPlan($request)
	{
		$station = Station::find($request->input('station_id'));
		$datePlans = $request->input('date_planned');
		$distributions = [];

		foreach ($datePlans as $id => $date) {
			$distributions[] = new MonthlyDistribution([
				'agen_id' => $id,
				'allocation' => Agen::find($id)->quota_monthly,
				'date_planned' => $date
			]);
		}

		$station->monthlyDistributions()->saveMany($distributions);

		return $distributions;
	}

	public function validateBatch($inputs)
	{
		return Validator::make($inputs, [
			'date_planned.*' => 'date'
		]);
	}*/
}
