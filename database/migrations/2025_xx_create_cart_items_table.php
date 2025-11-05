public function up()
{
    Schema::create('cart_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
        $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
        $table->integer('quantity')->default(1);
        $table->timestamps();
    });
}
