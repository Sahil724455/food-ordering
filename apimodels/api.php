use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminRestaurantController;
use App\Http\Controllers\PaymentController;

Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

Route::get('restaurants', [MenuController::class,'restaurants']);
Route::get('restaurants/{slug}/menu', [MenuController::class,'menuByRestaurant']);
Route::get('menu-items/{id}', [MenuController::class,'show']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('cart', [CartController::class,'show']);
    Route::post('cart/add', [CartController::class,'add']);
    Route::post('cart/update', [CartController::class,'update']);
    Route::post('cart/remove', [CartController::class,'remove']);

    Route::post('orders', [OrderController::class,'placeOrder']);
    Route::get('orders/{id}', [OrderController::class,'show']);
    Route::get('orders', [OrderController::class,'index']);

    Route::post('payments/dummy', [PaymentController::class,'charge']); // dummy payment
});

// Admin routes (protected & can be role-guarded)
Route::middleware(['auth:sanctum','can:manage-restaurants'])->prefix('admin')->group(function(){
    Route::apiResource('restaurants', AdminRestaurantController::class);
    Route::apiResource('restaurants.menu', AdminRestaurantController::class); // or separate controller
});
use App\Http\Controllers\MenuItemController;

Route::apiResource('menu-items', MenuItemController::class);
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Backend is working!'
    ]);
});
