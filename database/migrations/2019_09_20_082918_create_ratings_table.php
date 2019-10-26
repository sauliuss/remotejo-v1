<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->float('total_rating', 3, 1)->nullable();
            $table->float('company_culture', 3, 1)->nullable();
            $table->float('work_life_balance', 3, 1)->nullable();
            $table->float('pay_and_benefit', 3, 1)->nullable();
            $table->float('management', 3, 1)->nullable();
            $table->float('career_opportunities', 3, 1)->nullable();
            $table->string('rating_source')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
