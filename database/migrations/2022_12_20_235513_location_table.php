<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->Integer('user_id');
            $table->string('company_name')->nullable(true);
            $table->string('immatriculation')->nullable(true);
            $table->string('occupational_medicine')->nullable(true);
            $table->string('address1')->nullable(true);
            $table->string('address2')->nullable(true);
            $table->Integer('postcode')->nullable(true);
            $table->string('city')->nullable(true);
            $table->string('country')->nullable(true);
            $table->string('shift_id')->nullable(true);
            $table->string('selectedconvention')->nullable(true);
            $table->string('selectedcodeAPE')->nullable(true);
            $table->Integer('status')->nullable(true);
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
        //
        Schema::dropIfExists('locations');
    }
};
