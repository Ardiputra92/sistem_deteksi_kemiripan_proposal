<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThresholdsTable extends Migration
{
    public function up()
    {
        Schema::create('thresholds', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->default(25); // kolom untuk nilai threshold
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thresholds');
    }
}

