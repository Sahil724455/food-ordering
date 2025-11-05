public function up()
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
        $table->integer('quantity')->default(1);
        $table->decimal('unit_price', 8, 2);
        $table->decimal('line_total', 10, 2);
        $table->timestamps();
    });
}
