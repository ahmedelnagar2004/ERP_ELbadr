<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('files')) {
            // Add missing columns to files table
            if (!Schema::hasColumn('files', 'filename')) {
                Schema::table('files', function (Blueprint $table) {
                    $table->string('filename')->after('id');
                });
            }

            if (!Schema::hasColumn('files', 'size')) {
                Schema::table('files', function (Blueprint $table) {
                    $table->integer('size')->after('ext');
                });
            }

            if (!Schema::hasColumn('files', 'mime_type')) {
                Schema::table('files', function (Blueprint $table) {
                    $table->string('mime_type')->after('size');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('files')) {
            Schema::table('files', function (Blueprint $table) {
                $table->dropColumn(['filename', 'size', 'mime_type']);
            });
        }
    }
};
