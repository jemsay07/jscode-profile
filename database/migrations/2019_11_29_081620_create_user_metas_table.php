<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('birthday');
            $table->string('age');
            $table->string('address');
            $table->longText('contact_number');
            $table->longText('social_meta'); //Array
            $table->longText('bio');
            /**education*/
            $table->longText('schools_meta'); //Array
            /**Skills*/
            $table->string('skill_type');
            $table->longText('skills_meta'); //Array
            /**Working-Exp*/
            $table->longText('work_exp_meta'); //Array
            $table->longText('profile_id'); //Array
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
        Schema::dropIfExists('user_metas');
    }
}
