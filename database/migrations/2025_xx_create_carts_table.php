public function up()
{
    Schema::create('carts', function (Blueprint $table) {
        $table->id();
        
    $table->unsignedBigInteger('user_id');
$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

    });
}
