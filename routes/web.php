    <?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\ProductController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\TestTypeController;
    use App\Http\Controllers\TestController;
    use App\Http\Controllers\SearchController;
use App\Http\Controllers\DashboardController;


    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.attempt');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
    Route::resource('products', ProductController::class);

    Route::resource('test-types', TestTypeController::class)->except(['show']);

    Route::resource('tests', TestController::class);

    Route::patch('/tests/{test}/status', [TestController::class, 'updateStatus'])
        ->name('tests.updateStatus');

        Route::get('/search', [SearchController::class, 'index'])->name('search.index');


    });