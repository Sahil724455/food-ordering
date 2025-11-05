public function charge(Request $req) {
    // very small simulator: accept if amount > 0 and card_number ends with even digit
    $order = Order::find($req->order_id);
    if(!$order) return response()->json(['message'=>'Order not found'],404);

    // Simulate processing
    $card = $req->card_number ?? '';
    $last = substr($card, -1);
    $success = is_numeric($last) && ((int)$last % 2 === 0);

    // Simulate network delay with sleep in dev only? (skip in production)
    if($success){
        // return fake transaction id
        return response()->json(['status'=>'success','transaction_id'=>Str::uuid()],200);
    } else {
        return response()->json(['status'=>'failed','reason'=>'card declined (dummy)'],402);
    }
}
