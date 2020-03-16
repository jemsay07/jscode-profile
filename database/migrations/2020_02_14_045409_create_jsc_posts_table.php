<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJscPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsc_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('post_parent');
            $table->integer('post_media_id')->nullable();
            $table->integer('post_author');
            $table->string('post_title');
            $table->longText('post_content')->nullable();
            $table->string('post_excerpt')->nullable();
            $table->string('post_status');
            $table->string('post_type');
            $table->string('post_name');
            $table->string('guid');
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
        Schema::dropIfExists('jsc_posts');
    }
}
