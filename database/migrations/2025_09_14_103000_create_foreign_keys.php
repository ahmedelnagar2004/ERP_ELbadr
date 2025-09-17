<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('items', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('items', function(Blueprint $table) {
			$table->foreign('unit_id')->references('id')->on('units')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('sales', function(Blueprint $table) {
			$table->foreign('client_id')->references('id')->on('clients')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('sales', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('sales', function(Blueprint $table) {
			$table->foreign('safe_id')->references('id')->on('safes')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('safe_transactions', function(Blueprint $table) {
			$table->foreign('safe_id')->references('id')->on('safes')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('safe_transactions', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('sale_items', function(Blueprint $table) {
			$table->foreign('sale_id')->references('id')->on('sales')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('sale_items', function(Blueprint $table) {
			$table->foreign('item_id')->references('id')->on('items')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('returns', function(Blueprint $table) {
			$table->foreign('sale_id')->references('id')->on('sales')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('returns', function(Blueprint $table) {
			$table->foreign('safe_id')->references('id')->on('safes')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('returns', function(Blueprint $table) {
			$table->foreign('user_Id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('return_items', function(Blueprint $table) {
			$table->foreign('return_id')->references('id')->on('returns')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('return_items', function(Blueprint $table) {
			$table->foreign('item_id')->references('id')->on('items')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('client_id')->references('id')->on('clients')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('sale_id')->references('id')->on('sales')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->foreign('item_id')->references('id')->on('items')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('shipping_addresses', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('items', function(Blueprint $table) {
			$table->dropForeign('items_category_id_foreign');
		});
		Schema::table('items', function(Blueprint $table) {
			$table->dropForeign('items_unit_id_foreign');
		});
		Schema::table('sales', function(Blueprint $table) {
			$table->dropForeign('sales_client_id_foreign');
		});
		Schema::table('sales', function(Blueprint $table) {
			$table->dropForeign('sales_user_id_foreign');
		});
		Schema::table('sales', function(Blueprint $table) {
			$table->dropForeign('sales_safe_id_foreign');
		});
		Schema::table('safe_transactions', function(Blueprint $table) {
			$table->dropForeign('safe_transactions_safe_id_foreign');
		});
		Schema::table('safe_transactions', function(Blueprint $table) {
			$table->dropForeign('safe_transactions_user_id_foreign');
		});
		Schema::table('sale_items', function(Blueprint $table) {
			$table->dropForeign('sale_items_sale_id_foreign');
		});
		Schema::table('sale_items', function(Blueprint $table) {
			$table->dropForeign('sale_items_item_id_foreign');
		});
		Schema::table('returns', function(Blueprint $table) {
			$table->dropForeign('returns_sale_id_foreign');
		});
		Schema::table('returns', function(Blueprint $table) {
			$table->dropForeign('returns_safe_id_foreign');
		});
		Schema::table('returns', function(Blueprint $table) {
			$table->dropForeign('returns_user_Id_foreign');
		});
		Schema::table('return_items', function(Blueprint $table) {
			$table->dropForeign('return_items_return_id_foreign');
		});
		Schema::table('return_items', function(Blueprint $table) {
			$table->dropForeign('return_items_item_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_client_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_sale_id_foreign');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->dropForeign('order_items_order_id_foreign');
		});
		Schema::table('order_items', function(Blueprint $table) {
			$table->dropForeign('order_items_item_id_foreign');
		});
		Schema::table('shipping_addresses', function(Blueprint $table) {
			$table->dropForeign('shipping_addresses_order_id_foreign');
		});
	}
}