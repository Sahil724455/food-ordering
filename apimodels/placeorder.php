public function rules(){
    return [
        'restaurant_id' => 'required|exists:restaurants,id',
        'items' => 'required|array|min:1',
        'items.*.menu_item_id' => 'required|exists:menu_items,id',
        'items.*.quantity' => 'required|integer|min:1|max:20',
        'delivery_address' => 'required|array',
        'delivery_address.line1' => 'required|string',
        'payment_method' => 'required|string|in:dummy',
    ];
}
