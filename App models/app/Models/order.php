class Order extends Model {
    use HasFactory;
    protected $fillable = ['user_id','restaurant_id','total','status','delivery_address'];
    protected $casts = ['delivery_address' => 'array'];

    public function items(){ return $this->hasMany(OrderItem::class); }
}
