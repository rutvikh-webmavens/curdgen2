<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('field_type');
            $table->string('field_name');
            $table->string('field_title');
            $table->boolean('in_list')->default(0)->nullable();
            $table->boolean('in_create')->default(0)->nullable();
            $table->boolean('in_edit')->default(0)->nullable();
            $table->boolean('is_required')->default(0)->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
