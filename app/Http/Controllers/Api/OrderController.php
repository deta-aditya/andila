<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\SubscheduleRepository;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * The order repository instance.
     *
     * @var OrderRepository
     */
    protected $orders;

	/**
     * The subschedule repository instance.
     *
     * @var SubscheduleRepository
     */
    protected $subschedules;

	/**
     * Create a new controller instance.
     *
     * @param  OrderRepository  $orders
     * @param  SubscheduleRepository  $subschedules
     * @return void
     */
    public function __construct(
        OrderRepository $orders,
        SubscheduleRepository $subschedules)
    {
        $this->orders = $orders;
        $this->subschedules = $subschedules;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid = $this->orders->valid($request->all(), 'Index', [
            'quantity' => $request->input('quantity', null),
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->orders->index($request->all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function single(Request $request)
    {
        $valid = $this->orders->valid($request->all(), 'Single', [
            'schedule_id' => $request->input('schedule_id', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->orders->single($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Order $order)
    {
        $valid = $this->orders->valid($request->all(), 'Show');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->orders->show($order, $request->all()), 200);
    }

    /**
     * Display subschedules of the specified resource.
     *
     * @param  Request  $request
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function subschedules(Request $request, Order $order)
    {
        $params = array_add($request->all(), 'order', $order->id);
        $valid = $this->subschedules->valid($params, 'Index', [
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subschedules->index($request->all()), 200);
    }

    /**
     * "Accept" the specified resource.
     *
     * @param  Request  $request
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, Order $order)
    {
        $valid = $this->orders->valid([
            'order_id' => $order->id
        ], 'Accept');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->orders->accept($order), 200);
    }

    /**
     * "Accept" multiple resources.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accepts(Request $request)
    {        
        $valid = $this->orders->valid($request->all(), 'Accepts');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->orders->accepts($request->ids), 200);
    }
}
