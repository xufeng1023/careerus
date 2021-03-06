<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('catagory_id');
            $table->unsignedInteger('company_id');
            $table->string('identity');
            $table->string('title');
            $table->string('chinese_title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('chinese_description')->nullable();
            $table->string('location');
            $table->string('url')->nullable();
            $table->enum('job_type', ['Full-time', 'Part-time', 'Internship']);
            $table->string('copied_from')->nullable();
            $table->unsignedTinyInteger('sponsor_rate')->default(0);
            $table->unsignedTinyInteger('sponsor_odds')->default(0);
            $table->boolean('recommended')->default(0);
            $table->timestamps();
            $table->date('end_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
