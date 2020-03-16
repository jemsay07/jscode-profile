<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJscMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsc_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('attach_org_filename');
            $table->string('attach_filename');
            $table->string('attach_content')->nullable();
            $table->string('attach_excerpt')->nullable();
            $table->string('attach_extension')->nullable();
            $table->string('attach_mime_type')->nullable();
            $table->string('attach_image_size')->nullable();
            $table->longText('attach_image_alt')->nullable();
            $table->string('attach_url')->nullable();
            $table->longText('attachment_metadata')->nullable();
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
        Schema::dropIfExists('jsc_media');
    }
}
