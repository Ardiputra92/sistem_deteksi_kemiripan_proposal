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
        Schema::table('fingerprints', function (Blueprint $table) {
            $table->integer('total_fingerprint')->nullable()->after('fingerprint');
            $table->integer('total_ngram')->nullable()->after('total_fingerprint');
            $table->integer('total_hash')->nullable()->after('total_ngram');
            $table->integer('total_window')->nullable()->after('total_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fingerprints', function (Blueprint $table) {
            $table->dropColumn('total_fingerprint');
            $table->dropColumn('total_ngram');
            $table->dropColumn('total_hash');
            $table->dropColumn('total_window');
        });
    }
};
