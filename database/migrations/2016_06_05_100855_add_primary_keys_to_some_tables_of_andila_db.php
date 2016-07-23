<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryKeysToSomeTablesOfAndilaDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create primary key 'id' to addresses table
        Schema::table('addresses', function (Blueprint $table) {
            $table->increments('id');
        });

        // Create primary key 'id' to attachments table
        Schema::table('attachments', function (Blueprint $table) {
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop primary key 'id' in addresses table
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // Drop primary key 'id' in attachments table
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
