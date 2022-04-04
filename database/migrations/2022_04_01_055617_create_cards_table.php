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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four_number', 4)->nullable();
            $table->integer('card_expiry_month')->nullable();
            $table->integer('card_expiry_year')->nullable();
            $table->string('stripe_charge_id')->nullable();
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
        Schema::dropIfExists('cards');
    }
};
