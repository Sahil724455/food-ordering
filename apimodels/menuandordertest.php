public function test_user_can_browse_menu_and_place_order()
{
    // seed a restaurant and menu
    $restaurant = Restaurant::factory()->create();
    $item = MenuItem::factory()->create(['restaurant_id'=>$restaurant->id,'price'=>100]);

    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    // Browse menu
    $res = $this->getJson("/api/restaurants/{$restaurant->slug}/menu");
    $res->assertStatus(200)->assertJsonFragment(['name'=>$item->name]);

    // Place order
    $payload = [
        'restaurant_id' => $restaurant->id,
        'items' => [
            ['menu_item_id'=>$item->id,'quantity'=>2]
        ],
        'delivery_address'=>['line1'=>'Test road','city'=>'Kathmandu'],
        'payment_method'=>'dummy',
        'card_number'=>'4242424242424242'
    ];

    $res2 = $this->withHeader('Authorization','Bearer '.$token)->postJson('/api/orders',$payload);
    $res2->assertStatus(201)->assertJsonPath('order.total',200);
}
