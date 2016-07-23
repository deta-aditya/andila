<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesForAndilaDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create "users" table
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('handleable');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });

        // Create "stations" table
        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('location');
            $table->string('type');
            $table->timestamps();
        });

        // Create "agents" table
        Schema::create('agents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('executive');
            $table->string('location');
            $table->integer('monthly_max')->unsigned();
            $table->boolean('active')->default(0);
            $table->timestamps();
        });

        // Create "stands" table
        Schema::create('stands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id')->unsigned();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('owner');
            $table->string('location');
            $table->integer('daily_max')->unsigned();
            $table->boolean('active')->default(0);
            $table->timestamps();
        });

        // Create "addresses" table
        Schema::create('addresses', function (Blueprint $table) {
            $table->morphs('addressable');
            $table->string('province');
            $table->string('regency')->nullable();
            $table->string('district')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('detail')->nullable();
            $table->char('postal_code', 5)->nullable();
            $table->nullableTimestamps();
        });

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

        // Create "messages" table
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id')->unsigned();
            $table->integer('receiver_id')->unsigned();
            $table->string('subject')->nullable();
            $table->longtext('content');
            $table->boolean('draft')->default(0);
            $table->boolean('starred')->default(0);
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
        });

        // Create "attachments" table
        Schema::create('attachments', function (Blueprint $table) {
            $table->integer('message_id')->unsigned();
            $table->string('url');
            $table->dateTime('uploaded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop all tables
        Schema::drop('users');
        Schema::drop('stations');
        Schema::drop('agents');
        Schema::drop('stands');
        Schema::drop('addresses');
        Schema::drop('distributions');
        Schema::drop('subdistributions');
        Schema::drop('messages');
        Schema::drop('attachments');
    }
}
