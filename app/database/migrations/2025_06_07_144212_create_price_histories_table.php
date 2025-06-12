<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceHistoriesTable extends Migration
{
    public function up()
{
    Schema::create('price_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedBigInteger('post_id'); 
        $table->integer('price');
        $table->date('recorded_at');
        $table->timestamps();
        $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
    });
}


    public function down()
    {
        Schema::dropIfExists('price_histories');
    }
}
