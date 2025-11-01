public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
        $table->decimal('total', 10, 2);
        $table->string('status')->default('pending'); // pending, paid, preparing, delivered, cancelled
        $table->json('delivery_address')->nullable();
        $table->timestamps();
    });
}
