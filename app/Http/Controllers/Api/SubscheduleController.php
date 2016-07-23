<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\SubscheduleRepository;
use App\Repositories\ReportRepository;
use App\Models\Subschedule;

class SubscheduleController extends Controller
{
    /**
     * The subschedule repository instance.
     *
     * @var SubscheduleRepository
     */
    protected $subschedules;

	/**
     * The report repository instance.
     *
     * @var ReportRepository
     */
    protected $reports;

    /**
     * Create a new controller instance.
     *
     * @param  SubscheduleRepository  $subschedules
     * @param  ReportRepository  $reports
     * @return void
     */
    public function __construct(
        SubscheduleRepository $subschedules,
        ReportRepository $reports)
    {
        $this->subschedules = $subschedules;
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
        $valid = $this->subschedules->valid($request->all(), 'Index', [
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subschedules->index($request->all()), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Subschedule  $subschedule
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subschedule $subschedule)
    {
        $valid = $this->subschedules->valid($request->all(), 'Show');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subschedules->show($subschedule, $request->all()), 200);
    }

    /**
     * Store a new report in the specified storage.
     *
     * @param  Request  $request
     * @param  Subschedule  $subschedule
     * @return \Illuminate\Http\Response
     */
    public function singleReport(Request $request, Subschedule $subschedule)
    {
        $params = array_add($request->all(), 'subschedule_id', $subschedule->id);
        $valid = $this->reports->valid($params, 'Single', [
            'subschedule_id' => $subschedule->id,
            'allocated_qty' => $request->input('allocated_qty', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->reports->single($params), 201);
    }
}
