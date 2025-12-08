<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            'group' => 'sales',
            'name' => 'allow_negative_stock',
            'locked' => false,
            'payload' => json_encode(false),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('group', 'sales')
            ->where('name', 'allow_negative_stock')
            ->delete();
    }
};
