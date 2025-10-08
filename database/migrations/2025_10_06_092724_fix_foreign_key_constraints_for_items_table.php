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
        // تعطيل فحص قيود المفاتيح الخارجية مؤقتاً
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // حذف قيود المفاتيح الخارجية الحالية
            $this->dropForeignKeyIfExists('items', 'items_category_id_foreign');
            $this->dropForeignKeyIfExists('items', 'items_unit_id_foreign');

            // إعادة إنشاء قيود المفاتيح الخارجية مع CASCADE لحذف العناصر المرتبطة
            if (Schema::hasTable('categories')) {
                DB::statement('ALTER TABLE items ADD CONSTRAINT items_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }

            if (Schema::hasTable('units')) {
                DB::statement('ALTER TABLE items ADD CONSTRAINT items_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }

        } catch (Exception $e) {
            // في حالة وجود خطأ، سنقوم بتسجيله
            \Illuminate\Support\Facades\Log::error('فشل في إصلاح قيود المفاتيح الخارجية: ' . $e->getMessage());
        }

        // إعادة تفعيل فحص قيود المفاتيح الخارجية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // تعطيل فحص قيود المفاتيح الخارجية مؤقتاً
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // حذف قيود المفاتيح الخارجية الحالية
            $this->dropForeignKeyIfExists('items', 'items_category_id_foreign');
            $this->dropForeignKeyIfExists('items', 'items_unit_id_foreign');

            // إعادة إنشاء قيود المفاتيح الخارجية بالإعدادات الأصلية
            if (Schema::hasTable('categories')) {
                DB::statement('ALTER TABLE items ADD CONSTRAINT items_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }

            if (Schema::hasTable('units')) {
                DB::statement('ALTER TABLE items ADD CONSTRAINT items_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }

        } catch (Exception $e) {
            // في حالة وجود خطأ، سنقوم بتسجيله
            \Illuminate\Support\Facades\Log::error('فشل في التراجع عن إصلاح قيود المفاتيح الخارجية: ' . $e->getMessage());
        }

        // إعادة تفعيل فحص قيود المفاتيح الخارجية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * حذف قيد المفتاح الخارجي إذا كان موجوداً
     */
    protected function dropForeignKeyIfExists($table, $constraintName)
    {
        try {
            $exists = DB::select(
                "SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_SCHEMA = ?
                AND TABLE_NAME = ?
                AND CONSTRAINT_NAME = ?
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
                [config('database.connections.mysql.database'), $table, $constraintName]
            );

            if (!empty($exists)) {
                DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$constraintName}");
            }
        } catch (Exception $e) {
            // تجاهل الأخطاء عند حذف قيود المفاتيح الخارجية
        }
    }
};
