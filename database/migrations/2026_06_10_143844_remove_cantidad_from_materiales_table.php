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
        Schema::table('material', function (Blueprint $table) {
            $table->dropColumn('cantidad');
        }
        );
    }

    public function down(): void
    {
        Schema::table('material', function (Blueprint $table) {
            $table->numeric('cantidad')->default(0)->after('nombre');
        });
    }
};
