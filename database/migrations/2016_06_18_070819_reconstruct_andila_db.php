<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReconstructAndilaDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop "distributions", "subdistributions"
        Schema::drop('distributions');
        Schema::drop('subdistributions');

        // Create "schedules" table
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('station_id')->unsigned();
            $table->integer('agent_id')->unsigned();
            $table->date('scheduled_date');
            $table->timestamps();
        });

        // Create "orders" table
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schedule_id')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->date('delivered_date')->nullable();
            $table->date('accepted_date')->nullable();
            $table->timestamps();
        });

        // Create "subschedules" table
        Schema::create('subschedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('subagent_id')->unsigned();
            $table->date('scheduled_date');
            $table->timestamps();
        });

        // Create "reports" table
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subschedule_id')->unsigned();
            $table->integer('allocated_qty')->unsigned();
            $table->integer('sales_household_qty')->unsigned()->nullable();
            $table->integer('sales_microbusiness_qty')->unsigned()->nullable();
            $table->integer('stock_empty_qty')->unsigned()->nullable();
            $table->integer('stock_filled_qty')->unsigned()->nullable();
            $table->dateTime('reported_at')->nullable();
            $table->timestamps();
        });

        // Create "report_retailer" table
        Schema::create('report_retailer', function (Blueprint $table) {
            $table->integer('report_id')->unsigned();
            $table->integer('retailer_id')->unsigned();
            $table->integer('sales_qty')->unsigned();
        });

        // Create "retailers" table
        Schema::create('retailers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        // Create "activities" table
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('content');
            $table->tinyInteger('type');
            $table->timestamps();
        });

        // Create "notifications" table
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('content');
            $table->tinyInteger('type');
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });

        // Edit "stations" table
        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        // Edit "agents" table
        Schema::table('agents', function (Blueprint $table) {
            $table->renameColumn('executive', 'owner');
            $table->dropColumn('monthly_max');
        });

        // Edit "stands" table
        Schema::table('stands', function (Blueprint $table) {
            $table->renameColumn('daily_max', 'contract_value');
        });

        // Edit "messages" table
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('importance', 'priority');
            $table->dateTime('read_at')->nullable();
        });

        // Rename "stands" table
        Schema::rename('stands', 'subagents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Create "distributions" table
        Schema::create('distributions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('station_id')->unsigned();
            $table->integer('agent_id')->unsigned();
            $table->integer('allocation')->unsigned()->nullable();
            $table->date('date_planned')->nullable();
            $table->date('date_shipped')->nullable();
            $table->dateTime('reported_at')->nullable();
            $table->timestamps();
        });

        // Create "subdistributions" table
        Schema::create('subdistributions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('distribution_id')->unsigned();
            $table->integer('stand_id')->unsigned();
            $table->integer('allocation')->unsigned()->nullable();
            $table->integer('allocated')->unsigned()->nullable();
            $table->date('date_planned')->nullable();
            $table->date('date_shipped')->nullable();
            $table->dateTime('reported_at')->nullable();
            $table->timestamps();
        });

        // Drop "schedules", "orders", "subschedules", "reports", "report_retailers", "retailers", "activities", "notifications"
        Schema::drop('schedules');
        Schema::drop('orders');
        Schema::drop('subschedules');
        Schema::drop('reports');
        Schema::drop('report_retailers');
        Schema::drop('retailers');
        Schema::drop('activities');
        Schema::drop('notifications');

        // Edit "stations" table
        Schema::table('stations', function (Blueprint $table) {
            $table->string('email');
        });

        // Edit "agents" table
        Schema::table('agents', function (Blueprint $table) {
            $table->renameColumn('owner', 'executive');
            $table->integer('monthly_max')->unsigned();
        });

        // Edit "stands" table
        Schema::table('stands', function (Blueprint $table) {
            $table->renameColumn('contract_value', 'daily_max');
        });

        // Edit "messages" table
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('priority', 'importance');
            $table->dropColumn('read_at');
        });

        // Rename "subagents" table
        Schema::rename('subagents', 'stands');
    }
}
