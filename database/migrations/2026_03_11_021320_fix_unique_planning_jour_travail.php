<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('plannings', function (Blueprint $table) {
        $table->dropUnique('plannings_jour_travail_unique');

        $table->unique(['user_id', 'jour_travail']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('plannings', function (Blueprint $table) {
        $table->dropUnique(['user_id', 'jour_travail']);
        $table->unique('jour_travail');
    });
    }
};
