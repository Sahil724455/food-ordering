public function placeOrder(PlaceOrderRequest $req) {
    $user = $req->user();
    $items = $req->items;

    // Calculate totals and validate availability
    $total = 0;
    $restaurantId = $req->restaurant_id;
    $orderItems = [];
    foreach($items as $it){
        $menu = MenuItem::findOrFail($it['menu_item_id']);
        if(!$menu->available) return response()->json(['message'=>'Item not available: '.$menu->name], 422);

        $line = $menu->price * $it['quantity'];
        $total += $line;
        $orderItems[] = [
            'menu_item_id' => $menu->id,
            'quantity' => $it['quantity'],
            'unit_price' => $menu->price,
            'line_total' => $line,
        ];
    }

    // Create order in DB within transaction
    DB::beginTransaction();
    try {
        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurantId,
            'total' => $total,
            'status' => 'pending',
            'delivery_address' => $req->delivery_address,
        ]);
        foreach($orderItems as $oi){
            $order->items()->create($oi);
        }
        DB::commit();
    } catch (\Exception $e){
        DB::rollBack();
        return response()->json(['message'=>'Order failed','error'=>$e->getMessage()],500);
    }

    // If payment method is dummy -> call PaymentController::charge
    if($req->payment_method === 'dummy'){
        $paymentResponse = app(PaymentController::class)->charge(new Request([
            'order_id'=>$order->id,
            'amount'=>$order->total,
            'card_number' => $req->card_number ?? '4242424242424242' // optional
        ]));
        // PaymentController should return JSON with success/failure
        if($paymentResponse->getStatusCode() !== 200) {
            $order->update(['status'=>'cancelled']);
            return $paymentResponse;
        }
        $order->update(['status'=>'paid']);
    }

    return response()->json(['order'=>$order->load('items')], 201);
}
