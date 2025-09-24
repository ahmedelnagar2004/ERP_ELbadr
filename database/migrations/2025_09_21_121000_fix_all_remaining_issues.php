<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Create sessions table if it doesn't exist
            $this->createSessionsTable();
            
            // Create cache table if it doesn't exist
            $this->createCacheTable();
            
            // Fix all foreign keys
            $this->fixAllForeignKeys();
            
        } catch (Exception $e) {
            Log::error('Database fix error: ' . $e->getMessage());
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    protected function createSessionsTable()
    {
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    protected function createCacheTable()
    {
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }
    }

    protected function fixAllForeignKeys()
    {
        // Fix items table foreign keys
        $this->fixItemsForeignKeys();
        
        // Fix sales table foreign keys
        $this->fixSalesForeignKeys();
        
        // Fix other table foreign keys
        $this->fixOtherForeignKeys();
    }

    protected function fixItemsForeignKeys()
    {
        if (!Schema::hasTable('items')) {
            return;
        }

        // Drop existing foreign keys if they exist
        $this->dropForeignKeyIfExists('items', 'items_category_id_foreign');
        $this->dropForeignKeyIfExists('items', 'items_unit_id_foreign');

        // Ensure columns are the correct type (INT UNSIGNED)
        DB::statement('ALTER TABLE items MODIFY category_id INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE items MODIFY unit_id INT UNSIGNED NOT NULL');

        // Add foreign key constraints
        if (Schema::hasTable('categories')) {
            DB::statement('ALTER TABLE items ADD CONSTRAINT items_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
        }

        if (Schema::hasTable('units')) {
            DB::statement('ALTER TABLE items ADD CONSTRAINT items_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
        }
    }

    protected function fixSalesForeignKeys()
    {
        if (!Schema::hasTable('sales')) {
            return;
        }

        // Drop existing foreign keys if they exist
        $this->dropForeignKeyIfExists('sales', 'sales_client_id_foreign');
        $this->dropForeignKeyIfExists('sales', 'sales_user_id_foreign');
        $this->dropForeignKeyIfExists('sales', 'sales_safe_id_foreign');

        // Ensure columns are the correct type (INT UNSIGNED)
        DB::statement('ALTER TABLE sales MODIFY client_id INT UNSIGNED NULL');
        DB::statement('ALTER TABLE sales MODIFY user_id INT UNSIGNED NULL');
        DB::statement('ALTER TABLE sales MODIFY safe_id INT UNSIGNED NULL');

        // Add foreign key constraints
        if (Schema::hasTable('clients')) {
            DB::statement('ALTER TABLE sales ADD CONSTRAINT sales_client_id_foreign FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL ON UPDATE CASCADE');
        }

        if (Schema::hasTable('users')) {
            DB::statement('ALTER TABLE sales ADD CONSTRAINT sales_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
        }

        if (Schema::hasTable('safes')) {
            DB::statement('ALTER TABLE sales ADD CONSTRAINT sales_safe_id_foreign FOREIGN KEY (safe_id) REFERENCES safes(id) ON DELETE SET NULL ON UPDATE CASCADE');
        }
    }

    protected function fixOtherForeignKeys()
    {
        // Safe transactions foreign keys
        if (Schema::hasTable('safe_transactions')) {
            $this->dropForeignKeyIfExists('safe_transactions', 'safe_transactions_safe_id_foreign');
            $this->dropForeignKeyIfExists('safe_transactions', 'safe_transactions_user_id_foreign');

            DB::statement('ALTER TABLE safe_transactions MODIFY safe_id INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE safe_transactions MODIFY user_id INT UNSIGNED NOT NULL');

            if (Schema::hasTable('safes')) {
                DB::statement('ALTER TABLE safe_transactions ADD CONSTRAINT safe_transactions_safe_id_foreign FOREIGN KEY (safe_id) REFERENCES safes(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }

            if (Schema::hasTable('users')) {
                DB::statement('ALTER TABLE safe_transactions ADD CONSTRAINT safe_transactions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }
        }

        // Sale items foreign keys
        if (Schema::hasTable('sale_items')) {
            $this->dropForeignKeyIfExists('sale_items', 'sale_items_sale_id_foreign');
            $this->dropForeignKeyIfExists('sale_items', 'sale_items_item_id_foreign');

            DB::statement('ALTER TABLE sale_items MODIFY sale_id INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE sale_items MODIFY item_id INT UNSIGNED NOT NULL');

            if (Schema::hasTable('sales')) {
                DB::statement('ALTER TABLE sale_items ADD CONSTRAINT sale_items_sale_id_foreign FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }

            if (Schema::hasTable('items')) {
                DB::statement('ALTER TABLE sale_items ADD CONSTRAINT sale_items_item_id_foreign FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
            }
        }

        // Returns foreign keys
        if (Schema::hasTable('returns')) {
            $this->dropForeignKeyIfExists('returns', 'returns_sale_id_foreign');
            $this->dropForeignKeyIfExists('returns', 'returns_safe_id_foreign');
            $this->dropForeignKeyIfExists('returns', 'returns_user_Id_foreign');

            DB::statement('ALTER TABLE returns MODIFY sale_id INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE returns MODIFY safe_id INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE returns MODIFY user_Id INT UNSIGNED NOT NULL');

            if (Schema::hasTable('sales')) {
                DB::statement('ALTER TABLE returns ADD CONSTRAINT returns_sale_id_foreign FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }

            if (Schema::hasTable('safes')) {
                DB::statement('ALTER TABLE returns ADD CONSTRAINT returns_safe_id_foreign FOREIGN KEY (safe_id) REFERENCES safes(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }

            if (Schema::hasTable('users')) {
                DB::statement('ALTER TABLE returns ADD CONSTRAINT returns_user_Id_foreign FOREIGN KEY (user_Id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }
        }

        // Return items foreign keys
        if (Schema::hasTable('return_items')) {
            $this->dropForeignKeyIfExists('return_items', 'return_items_return_id_foreign');
            $this->dropForeignKeyIfExists('return_items', 'return_items_item_id_foreign');

            DB::statement('ALTER TABLE return_items MODIFY return_id INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE return_items MODIFY item_id INT UNSIGNED NOT NULL');

            if (Schema::hasTable('returns')) {
                DB::statement('ALTER TABLE return_items ADD CONSTRAINT return_items_return_id_foreign FOREIGN KEY (return_id) REFERENCES returns(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }

            if (Schema::hasTable('items')) {
                DB::statement('ALTER TABLE return_items ADD CONSTRAINT return_items_item_id_foreign FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }
        }

        // Orders foreign keys
        if (Schema::hasTable('orders')) {
            $this->dropForeignKeyIfExists('orders', 'orders_client_id_foreign');
            $this->dropForeignKeyIfExists('orders', 'orders_sale_id_foreign');

            DB::statement('ALTER TABLE orders MODIFY client_id INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE orders MODIFY sale_id INT UNSIGNED NOT NULL');

            if (Schema::hasTable('clients')) {
                DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_client_id_foreign FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }

            if (Schema::hasTable('sales')) {
                DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_sale_id_foreign FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }
        }

        // Order items foreign keys
        if (Schema::hasTable('order_items')) {
            $this->dropForeignKeyIfExists('order_items', 'order_items_order_id_foreign');
            $this->dropForeignKeyIfExists('order_items', 'order_items_item_id_foreign');

            DB::statement('ALTER TABLE order_items MODIFY order_id INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE order_items MODIFY item_id INT UNSIGNED NOT NULL');

            if (Schema::hasTable('orders')) {
                DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }

            if (Schema::hasTable('items')) {
                DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_item_id_foreign FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }
        }

        // Shipping addresses foreign keys
        if (Schema::hasTable('shipping_addresses')) {
            $this->dropForeignKeyIfExists('shipping_addresses', 'shipping_addresses_order_id_foreign');

            DB::statement('ALTER TABLE shipping_addresses MODIFY order_id INT UNSIGNED NOT NULL');

            if (Schema::hasTable('orders')) {
                DB::statement('ALTER TABLE shipping_addresses ADD CONSTRAINT shipping_addresses_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
            }
        }
    }

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
            // Ignore errors when dropping foreign keys
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a fix migration, so we don't implement down()
    }
};
