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
        Schema::table('consumption_histories', function (Blueprint $table) {
            $table->integer('protein')->nullable()->after('calories');
            $table->integer('carbo')->nullable()->after('protein');
            $table->integer('fat')->nullable()->after('carbo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumption_histories', function (Blueprint $table) {
            $table->dropColumn(['protein', 'carbo', 'fat']);
        });
    }
};
