<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;
use App\Models\Report;

class ReportController extends Controller
{
    /**
     * The report repository instance.
     *
     * @var ReportRepository
     */
    protected $reports;

    /**
     * Create a new controller instance.
     *
     * @param  ReportRepository  $reports
     * @return void
     */
    public function __construct(ReportRepository $reports)
    {
        $this->reports = $reports;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid = $this->reports->valid($request->all(), 'Index', [
            'allocation' => $request->input('allocation', null),
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->reports->index($request->all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function single(Request $request)
    {
        $valid = $this->reports->valid($request->all(), 'Single', [
            'subschedule_id' => $request->input('subschedule_id', null),
            'allocated_qty' => $request->input('allocated_qty', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->reports->single($request->all()), 201);
    }

    /**
     * Bulk store multiple resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multiple(Request $request)
    {
        $allocated_qty = collect($request->all())->sum('allocated_qty');

        foreach ($request->all() as $single) {
            $sc = collect($single);
            $valid = $this->reports->valid($single, 'Single', [
                'subschedule_id' => $sc->get('subschedule_id', null),
                'allocated_qty' => $allocated_qty,
            ]);

            if ($valid->fails()) {
                return response()->json(['errors' => $valid->messages()], 422);
            }
        }

        return response()->json($this->reports->multiple($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Report $report)
    {
        return response()->json($this->reports->show($report, $request->all()), 200);
    }

    /**
     * Complete the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Report  $report
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, Report $report)
    {
        $valid = $this->reports->valid($request->all(), 'Complete', [
            'report' => $report,
            'sales_retailers' => $request->input('sales_retailers', null),
            'sales_household_qty' => $request->input('sales_household_qty', null),
            'sales_microbusiness_qty' => $request->input('sales_microbusiness_qty', null),
            'stock_empty_qty' => $request->input('stock_empty_qty', null),
            'stock_filled_qty' => $request->input('stock_filled_qty', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->reports->complete($report, $request->all()), 200);
    }

    /**
     * Complete multiple resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function completes(Request $request)
    {
        foreach ($request->all() as $single) {
            $sc = collect($single);
        	$valid = $this->reports->valid($single, 'Complete', [
                'report' => Report::find($single['report_id']),
                'sales_retailers' => $sc->get('sales_retailers', null),
                'sales_household_qty' => $sc->get('sales_household_qty', null),
                'sales_microbusiness_qty' => $sc->get('sales_microbusiness_qty', null),
                'stock_empty_qty' => $sc->get('stock_empty_qty', null),
                'stock_filled_qty' => $sc->get('stock_filled_qty', null),
            ]);

            if ($valid->fails()) {
                return response()->json(['errors' => $valid->messages()], 422);
            }
        }

        return response()->json($this->reports->completes($request->all()), 200);
    }
}
