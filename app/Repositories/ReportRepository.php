<?php

namespace App\Repositories;

use App\Models\Report;
use App\Models\Subschedule;
use App\Models\Retailer;
use App\Validations\ReportValidation;
use App\Repositories\Repository;
use Carbon\Carbon;
use Validator;

class ReportRepository extends Repository
{
	/**
     * The report validation instance.
     *
     * @var ReportValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  ReportValidation  $validation
     * @return void
     */
    public function __construct(ReportValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get all of the reports with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        // By default each orders will load its own "subschedule" and "retailers" data
        $query = $this->select(Report::with('subschedule', 'retailers'), $params, false);

        if ( array_has($params, 'station') ) {
            $query->ofStation($params['station']);
        }

        if ( array_has($params, 'agent') ) {
            $query->ofAgent($params['agent']);
        }

        if ( array_has($params, 'subagent') ) {
            $query->ofSubagent($params['subagent']);
        }

        if ( array_has($params, 'schedule') ) {
            $query->ofSchedule($params['schedule']);
        }

        if ( array_has($params, 'order') ) {
            $query->ofOrder($params['order']);
        }

        if ( array_has($params, 'reported') ) {
            $query->reported((bool)$params['reported']);
        }

        if ( array_has($params, 'allocation') ) {
            list($operator, $value) = explode(':', $params['allocation']);
            $query->hasAllocation($operator, $value);
        }

        if ( array_has($params, 'range') ) {
            $query->scheduledBetween(explode('_', $params['range']));
        }

        return $this->extractQuery($query, $params);
    }

	/**
     * Store a new report model.
     *
     * @param  array  $data
     * @return array
     */
    public function single($data)
    {
        $reportData = array_only($data, ['subschedule_id', 'allocated_qty']);

        $report = Report::create($reportData);

        return $this->extractResource($report, 'reports');
    }

    /**
     * Get the specified report model
     *
     * @param  Report  $report
     * @param  array  $params
     * @return array
     */
    public function show(Report $report, $params)
    {
        // By default it will load "subschedule" and "retailers" method
        $report->load('subschedule', 'retailers');

        return $this->extractResource($report, 'reports');
    }

	/**
     * Store multiple report models at once.
     *
     * @param  array  $data
     * @return array
     */
    public function multiple($data)
    {
        $results = [];

        foreach ($data as $d) {
            $results[] = $this->single($d);
        }

        return $results;
    }

    /**
     * Complete a report model.
     *
     * @param  Report  $report
     * @param  array  $params
     * @return array
     */
    public function complete(Report $report, $params)
    {	
        $completeData = array_only($params, ['sales_household_qty', 'sales_microbusiness_qty', 'stock_empty_qty', 'stock_filled_qty']);

    	foreach ($params['sales_retailers'] as $sr) {
            $retailer = Retailer::firstOrCreate(['name' => $sr['retailer_name']]);            
    		$report->retailers()->attach($retailer, ['sales_qty' => $sr['sales_qty']]);
    	}

        $report->update($completeData);
        $report->reported_at = Carbon::now()->toDateTimeString();
    	$report->save();
    	$report->load('retailers');

        return $this->extractResource($report, 'reports');
    }

    /**
     * Complete multiple report models at once.
     *
     * @param  array  $data
     * @return array
     */
    public function completes($data)
    {
        $results = [];

        foreach ($data as $d) {
            $results[] = $this->complete(Report::find($d['report_id']), $d);
        }

        return $results;
    }
}
