<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('reservations', 'rejection_reason')) {
            Schema::table('reservations', function (Blueprint $table) {
                // Ajout de la colonne nécessaire pour le point 3.3
                $table->text('rejection_reason')->nullable()->after('justification');
            });
        }
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};