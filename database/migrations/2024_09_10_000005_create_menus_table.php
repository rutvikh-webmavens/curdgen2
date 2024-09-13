<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('model_name');
            $table->string('title');
            $table->string('sort_order')->nullable();
            $table->boolean('soft_delete')->default(0)->nullable();
            $table->boolean('create')->default(0)->nullable();
            $table->boolean('edit')->default(0)->nullable();
            $table->boolean('show')->default(0)->nullable();
            $table->boolean('delete')->default(0)->nullable();
            $table->boolean('column_search')->default(0)->nullable();
            $table->string('entries_per_page');
            $table->string('order_by_field_name');
            $table->string('order_by_desc');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
