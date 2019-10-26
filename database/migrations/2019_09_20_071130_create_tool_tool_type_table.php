<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToolToolTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tool_tool_type', function (Blueprint $table) {
            $table->bigInteger('tool_id')->unsigned();
            $table->foreign('tool_id')->references('id')->on('tools');
            $table->bigInteger('tool_type_id')->unsigned();
            $table->foreign('tool_type_id')->references('id')->on('tool_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tool_tool_type');
    }
}
