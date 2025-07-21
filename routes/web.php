<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LoginController;

// Route::get('/', function () {
//     return view('welcome');
// });


// ✅ Home page
Route::get('/', [IndexController::class, 'home'])->name('home');

// ✅ Auth pages
Route::get('/login', [IndexController::class, 'login'])->name('login');
Route::get('/register', [IndexController::class, 'register'])->name('register');

// ✅ Logout
Route::get('/logout', [IndexController::class, 'logout'])->name('logout');

// ✅ Dynamic index pages (MUST BE LAST to avoid conflicts) OR add REGEX to prevent it from serving reserved routes
Route::get('/{page}', [IndexController::class, 'dynamic'])
->where('page', '^(?!dashboard|user|admin|blog|contact).*');






// ✅ Simple middleware-like closure for session check
$sessionCheck = function ($routeAction, $json = false) {
    return function (\Illuminate\Http\Request $request) use ($routeAction, $json) {
        if (!session()->has('user')) {
            if ($json) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect('/login');
        }

        // ✅ Collect all route parameters (like {page}, {id}, etc.)
        $routeParams = $request->route()->parameters();

        // ✅ Merge request + route params
        $params = array_merge(['request' => $request], $routeParams);

        // ✅ Resolve controller instance
        if (is_array($routeAction)) {
            return app()->call([app($routeAction[0]), $routeAction[1]], $params);
        }

        // ✅ If it's already a Closure
        return app()->call($routeAction, $params);
    };
};




// ✅ Dashboard routes with session check
Route::get('/dashboard', $sessionCheck([DashboardController::class, 'index']))->name('dashboard');
Route::get('/dashboard/{page}', $sessionCheck([DashboardController::class, 'show']))
    ->where('page', '[A-Za-z0-9\-_]+')
    ->name('dashboard.page');




// ✅ Admin API routes
Route::prefix('admin')->group(function () use ($sessionCheck) {
    Route::post('/register', [AdminController::class, 'register']);

    // ✅ Wrap API routes with sessionCheck(json = true)
    Route::get('/getAllUsers', $sessionCheck([AdminController::class, 'getAllUsers'], true));
    Route::get('/getAdminById/{id}', $sessionCheck([AdminController::class, 'getById'], true));
    Route::put('/updateAdmin', $sessionCheck([AdminController::class, 'updateAdmin'], true));
    Route::post('/addWashHistory', $sessionCheck([AdminController::class, 'addWashHistory'], true));
    Route::get('/getWashHistory', $sessionCheck([AdminController::class, 'getAllWashHistory'], true));
    Route::post('/updateWashHistory', $sessionCheck([AdminController::class, 'updateWashHistory'], true));
    Route::delete('/deleteWashHistory/{id}', $sessionCheck([AdminController::class, 'deleteWashHistory'], true));
    Route::patch('/changePassword', $sessionCheck([AdminController::class, 'changePassword'], true));
    Route::get('/fetchSubscriptionHistory', $sessionCheck([AdminController::class, 'fetchSubscriptionHistory'], true));
    Route::post('/create-blog', $sessionCheck([BlogController::class, 'createBlog'], true));
});






// ✅ User API routes
Route::prefix('user')->group(function () use ($sessionCheck) {
    Route::post('/register', [UserController::class, 'register']);

    Route::get('/getUserById/{id}', $sessionCheck([UserController::class, 'getById'], true));
    Route::put('/updateUser', $sessionCheck([UserController::class, 'updateUser'], true));
    Route::patch('/changePassword', $sessionCheck([UserController::class, 'changePassword'], true));
    Route::get('/getUserWashHistory', $sessionCheck([UserController::class, 'getUserWashHistory'], true));
    Route::post('/auto-billing-toggle', $sessionCheck([UserController::class, 'toggleAutoBilling'], true));
    Route::get('/fetchMyCurrentSubscription', $sessionCheck([UserController::class, 'fetchMyCurrentSubscription'], true));
    Route::post('/initiatePayment', $sessionCheck([UserController::class, 'initiatePayment'], true));
    Route::get('/payment/callback', $sessionCheck([UserController::class, 'handlePaymentCallback'], true));
    Route::get('/check-subscription-status/{reference}', $sessionCheck([UserController::class, 'checkSubscriptionStatus'], true));
    Route::post('/cancelSubscription', $sessionCheck([UserController::class, 'cancelSubscription'], true));
    Route::get('/fetchSubscriptionHistory', $sessionCheck([UserController::class, 'fetchSubscriptionHistory'], true));
    Route::get('/wash-history', $sessionCheck([UserController::class, 'getUserWashHistory'], true));
});







// Send Email API route
Route::prefix('contact')->group(function () {
    Route::post('/sendEmailAndSave', [ContactController::class, 'submitContactForm']);
});





// Blog API routes
Route::prefix('blog')->group(function () {
    // ✅ Create blog post (with optional image upload)
    Route::post('/createBlog', [BlogController::class, 'createBlog']);

    // ✅ List all blogs
    Route::get('/getAllBlogs', [BlogController::class, 'getAllBlogs']);

    // ✅ Latest news (latest 10 blogs)
    Route::get('/latestNews', [BlogController::class, 'latestNews']);

    // ✅ Get single blog by ID
    Route::get('/getSingleBlog/{id}', [BlogController::class, 'getSingleBlog']);

    // ✅ Update a blog post (with optional image upload)
    Route::post('/updateSingleBlog/{id}', [BlogController::class, 'updateBlog']);

    // ✅ Delete a blog post
    Route::delete('/deleteSingleBlog/{id}', [BlogController::class, 'deleteBlog']);

    // ✅ Add a comment to a blog
    Route::post('/{id}/addComment', [BlogController::class, 'addComment']);
});





// Login API POST route
Route::post('/login', [LoginController::class, 'login']);




Route::any('.well-known/{any?}', function () {
    return response()->json(['error' => 'Not Found'], 404);
})->where('any', '.*');





