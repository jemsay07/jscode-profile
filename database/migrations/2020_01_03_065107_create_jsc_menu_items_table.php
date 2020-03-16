<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJscMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsc_menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('menu_id');
            $table->integer('parent_id')->nullable(0);
            $table->integer('menu_item_order');
            $table->string('menu_item_type');
            $table->string('menu_item_status');
            $table->longText('menu_item_title');
            $table->longText('menu_item_desc')->nullable();
            $table->string('menu_item_object')->nullable();
            $table->longText('menu_item_url')->nullable();
            $table->string('menu_item_xfn')->nullable();
            // $table->string('menu_item_attr')->nullable();
            $table->string('menu_item_attr_title')->nullable();
            $table->string('menu_item_css')->nullable();
            $table->string('menu_item_id')->nullable();
            $table->integer('menu_item_tab')->nullable(0);
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
        Schema::dropIfExists('jsc_menu_items');
    }
}
