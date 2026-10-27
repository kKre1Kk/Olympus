<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gods', function (Blueprint $table) {
            $table->date('recorded_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('gods', function (Blueprint $table) {
            $table->dropColumn('recorded_at');
        });
    }
};