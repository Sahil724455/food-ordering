class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','description','address'];

    public function menuItems() {
        return $this->hasMany(MenuItem::class);
    }
}
