<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullHandleableTypeInUsersTableOfAndilaDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Make the handleable_type in users table nullable
        Schema::table('users', function (Blueprint $table) {
            $table->string('handleable_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Make the handleable_type in users table unnullable
        Schema::table('users', function (Blueprint $table) {
            $table->string('handleable_type')->nullable(false)->change();
        });
    }
}
