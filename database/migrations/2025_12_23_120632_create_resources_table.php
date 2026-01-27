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
    Schema::create('resources', function (Illuminate\Database\Schema\Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('type');
    $table->string('category');
    $table->integer('cpu')->nullable();
    $table->integer('ram')->nullable();
    $table->integer('storage_capacity')->nullable();
    $table->string('storage_type')->nullable();
    $table->string('bandwidth')->nullable();
    $table->string('os')->nullable();
    $table->string('location')->nullable();
    $table->string('status')->default('disponible');
    $table->foreignId('manager_id')->constrained('users');
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
        Schema::dropIfExists('resources');
    }
};
