public function up()
{
    Schema::create('menu_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 8, 2);
         $table->float('rating')->default(0);
        $table->boolean('available')->default(true);
        $table->timestamps();
    });
}

