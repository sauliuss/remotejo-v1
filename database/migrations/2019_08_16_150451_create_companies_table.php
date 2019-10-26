<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('logo')->nullable();
            $table->text('description_short')->nullable();
            $table->text('description_full')->nullable();
            $table->tinyInteger('remote_level')->default(0);
            $table->string('headquaters')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('size')->nullable();
            $table->string('founding_years')->nullable();
            $table->string('url')->nullable();
            $table->text('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('github')->nullable();
            $table->string('producthunt')->nullable();
            $table->string('youtube')->nullable();
            $table->boolean('is_claimed')->default(0);
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
        Schema::dropIfExists('companies');
    }
}
