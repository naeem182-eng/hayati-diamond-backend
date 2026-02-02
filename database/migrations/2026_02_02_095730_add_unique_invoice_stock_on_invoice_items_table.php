Schema::table('invoice_items', function (Blueprint $table) {
    $table->unique(['invoice_id', 'stock_item_id']);
});
