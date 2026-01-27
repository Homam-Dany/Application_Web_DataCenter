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
    Schema::table('resources', function (Blueprint $table) {
        $table->boolean('en_maintenance')->default(false);
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('resources', function (Blueprint $table) {
        $table->dropColumn('en_maintenance');
    });
}
};
