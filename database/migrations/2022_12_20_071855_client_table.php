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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('company')->nullable(true);
            $table->string('siret')->nullable(true);
            $table->string('tva_intracom')->nullable(true);
            $table->string('address1')->nullable(true);
            $table->string('address2')->nullable(true);
            $table->string('postcode')->nullable(true);
            $table->string('city')->nullable(true);
            $table->string('country')->nullable(true);
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
        Schema::dropIfExists('clients');
    }
};
