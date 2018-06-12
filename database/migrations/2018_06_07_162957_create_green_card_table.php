<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGreenCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('green_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('country');
            $table->date('check_at');
            $table->string('filing_at')->nullable();
            $table->string('action_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('green_card');
    }
}
