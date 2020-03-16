<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJscOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsc_options', function (Blueprint $table) {
            $table->bigIncrements('option_id');
            $table->string('option_name', 255)->nullable();
            $table->longText('option_value')->nullable();
            $table->string('autoload', 100);
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
        Schema::dropIfExists('jsc_options');
    }
}
