<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('css');
            $table->string('date_format');
            $table->string('language');
            $table->string('model_location');
            $table->string('timezone');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
