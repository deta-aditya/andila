<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Schedule;
use App\Validations\OrderValidation;
use App\Repositories\Repository;
use App\Events\OrderWasAccepted;
use Carbon\Carbon;

class OrderRepository extends Repository
{
    /**
     * The order validation instance.
     *
     * @var OrderValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  OrderValidation  $validation
     * @return void
     */
    public function __construct(OrderValidation $validation)
    {
        $this->validation = $validation;
    }

	/**
     * Get all of the orders with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        // By default each orders will load its own "schedule" data
        $query = $this->select(Order::with('schedule'), $params, false);

        if ( array_has($params, 'station') ) {
            $query->ofStation($params['station']);
        }

        if ( array_has($params, 'agent') ) {
            $query->ofAgent($params['agent']);
        }

        if ( array_has($params, 'accepted') ) {
            $query->accepted((bool)$params['accepted']);
        }

        if ( array_has($params, 'quantity') ) {
            list($operator, $value) = explode(':', $params['quantity']);
            $query->hasQuantity($operator, $value);
        }

        if ( array_has($params, 'range') ) {
            $query->scheduledBetween(explode('_', $params['range']));
        }

        return $this->extractQuery($query, $params);
    }

	/**
     * Store a new order model.
     *
     * @param  array  $data
     * @return array
     */
    public function single($data)
    {
        $orderData = array_only($data, ['schedule_id']);

        $order = new Order($orderData);
        $schedule = Schedule::find($orderData['schedule_id']);

        // The quantity of the order will be the agent's contract value multiplies number of days in a month interval
        // It is hardcoded so the agent won't be able to order less or more than it
        // IT'S AN AWESOME FEATURE ISN'T IT !!?
        $order->quantity = $schedule->agent->contractValues() * $schedule->daysIntervalUntilNextMonth();
        $order->save();

        $schedule->order()->save($order);

        return $this->extractResource($order, 'orders');
    }

    /**
     * Get the specified order model
     *
     * @param  Order  $order
     * @param  array  $params
     * @return array
     */
    public function show(Order $order, $params)
    {
        // By default it will load "schedule" method
        $order->load('schedule');

        if ( array_has($params, 'station') ) {
            ! (bool)$params['station'] ?: $order->schedule->load('station'); 
        }

        if ( array_has($params, 'agent') ) {
            ! (bool)$params['agent'] ?: $order->schedule->load('agent'); 
        }

        if ( array_has($params, 'subschedules') ) {
            ! (bool)$params['subschedules'] ?: $order->load('subschedules'); 
        }

        return $this->extractResource($order, 'orders');
    }

    /**
     * "Accept" an order model.
     *
     * @param  Order  $order
     * @return array
     */
    public function accept(Order $order)
    {
        $order->accepted_date = Carbon::now()->toDateTimeString();
        $order->save();
        $order->load('schedule');
            
        // Run seed subschedule event
        event(new OrderWasAccepted($order));

        return $this->extractResource($order, 'orders');
    }

	/**
     * "Accept" multiple order models.
     *
     * @param  array  $ids
     * @return array
     */
    public function accepts($ids)
    {
        $results = [];

        foreach ($ids as $id) {
            $results[] = $this->accept(Order::find($id));
        }

        return $results;
    }
}
