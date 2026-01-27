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
    Schema::create('reservations', function (Illuminate\Database\Schema\Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('resource_id')->constrained();
    $table->dateTime('start_date');
    $table->dateTime('end_date');
    $table->string('status')->default('en_attente');
    $table->text('justification');
    $table->text('rejection_reason')->nullable();
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
        Schema::dropIfExists('reservations');
    }
};
