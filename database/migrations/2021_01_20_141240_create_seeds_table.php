<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeedsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('seeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('seed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seeds');
    }
}
