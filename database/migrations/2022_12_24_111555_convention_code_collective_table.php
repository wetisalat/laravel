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
        Schema::create('convention_collectives', function (Blueprint $table) {
            $table->id();
            $table->string('convention_collective');
            $table->string('rule1');
            $table->string('rule2');
            $table->string('rule3');
            $table->string('rule4');
            $table->string('rule5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('convention_collectives');
    }
};
