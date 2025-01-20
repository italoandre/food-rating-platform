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
        Schema::table('review_photos', function (Blueprint $table) {
            $table->renameColumn('filename', 'path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_photos', function (Blueprint $table) {
            $table->renameColumn('path', 'filename');
        });
    }
};
