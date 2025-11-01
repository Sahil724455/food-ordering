public function rules(){
    return [
        'menu_item_id' => 'required|exists:menu_items,id',
        'quantity' => 'required|integer|min:1|max:20',
    ];
}
