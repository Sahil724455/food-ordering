public function add(AddToCartRequest $req){
    $user = $req->user();
    $cart = Cart::firstOrCreate(['user_id'=>$user->id]);
    $item = $cart->items()->updateOrCreate(
        ['menu_item_id'=>$req->menu_item_id],
        ['quantity' => DB::raw("quantity + {$req->quantity}")]
    );
    return response()->json(['cart'=>$cart->load('items.menuItem')], 201);
}
