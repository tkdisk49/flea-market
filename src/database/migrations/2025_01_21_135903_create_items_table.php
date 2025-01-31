<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('price');
            $table->string('image');
            $table->text('description');
            $table->tinyInteger('condition')->comment('1:良好, 2:目立った傷や汚れなし, 3:やや傷や汚れあり, 4:状態が悪い');
            $table->string('brand')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:販売中, 2:売り切れ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
