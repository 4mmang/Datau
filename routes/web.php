<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageDatasetsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ContributeDatasetController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\DonationPaperController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginGithubController;
use App\Http\Controllers\LoginGoogleController;
use App\Http\Controllers\MyDatasetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Models\Article;
use App\Models\Dataset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');

Route::get('login', [AuthController::class, 'index'])
    ->name('login')
    ->middleware('guest');

Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('login/validation', [AuthController::class, 'validation']);

Route::get('register', [RegistrationController::class, 'index'])->middleware('guest');
Route::post('register/user', [RegistrationController::class, 'store']);

Route::get('/email/verify', function () {
    return view('verify-email');
})
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->intended('/');
})
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// login with google
Route::get('/auth/google/redirect', [LoginGoogleController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [LoginGoogleController::class, 'googleCallback']);

// login with github
Route::get('/auth/github/redirect', [LoginGithubController::class, 'githubRedirect']);
Route::get('/auth/github/callback', [LoginGithubController::class, 'githubCallback']);

// download dataset
Route::get('download/{id}', [DownloadController::class, 'download'])->middleware(['auth', 'verified']);

Route::get('datasets', [DatasetController::class, 'index']);
Route::get('detail/dataset/{id}', [DatasetController::class, 'show']);
Route::get('filter/{id}', [DatasetController::class, 'filter']);

Route::get('donation', [ContributeDatasetController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('sumbang-dataset');

Route::post('more/info', [ContributeDatasetController::class, 'moreInfo'])->middleware(['auth', 'verified']);
Route::post('donation/store', [ContributeDatasetController::class, 'store'])->middleware(['auth', 'verified']);
Route::get('my/dataset', [MyDatasetController::class, 'index'])->middleware(['auth', 'verified']);
Route::get('my/dataset/edit/{id}', [MyDatasetController::class, 'edit'])->middleware(['auth', 'verified']);
Route::post('more/info/my/dataset', [MyDatasetController::class, 'moreInfo'])->middleware(['auth', 'verified']);
Route::put('my/dataset/update/{id}', [MyDatasetController::class, 'update'])->middleware(['auth', 'verified']);
Route::get('my/dataset/{id}', [MyDatasetController::class, 'show'])->middleware(['auth', 'verified']);
Route::delete('delete/my/dataset/{id}', [MyDatasetController::class, 'destroy'])->middleware(['auth', 'verified']);
Route::post('donation/paper', [DonationPaperController::class, 'store'])->middleware(['auth', 'verified']);

Route::group(['middleware' => ['auth', 'verified', 'role:admin']], function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/manage/datasets', [ManageDatasetsController::class, 'index']);
    Route::get('admin/detail/dataset/{id}', [ManageDatasetsController::class, 'show']);
    Route::delete('admin/delete/dataset/{id}', [ManageDatasetsController::class, 'destroy']);

    Route::get('admin/edit/dataset/{id}', [ManageDatasetsController::class, 'edit']);
    Route::put('admin/update/dataset/{id}', [ManageDatasetsController::class, 'update']);

    Route::put('admin/validate/dataset/{id}', [ManageDatasetsController::class, 'valid']);
    Route::post('admin/invalid/dataset/{id}', [ManageDatasetsController::class, 'invalid']);

    Route::get('admin/manage/users', [UserController::class, 'index']);
    Route::put('admin/manage/user/{id}', [UserController::class, 'update']);
    Route::delete('admin/delete/user/{id}', [UserController::class, 'destroy']);

    Route::resource('admin/manage/articles', ArticleController::class);
});

Route::get('article/{id}', function ($id) {
    $article = Article::findOrFail($id);
    return view('article', compact('article'));
});

Route::get('forgot/password', function () {
    return view('auth.forgot-password');
})->middleware('guest');

Route::post('send/code/verification', [ForgotPasswordController::class, 'sendCodeVerification']);
Route::post('verify', [ForgotPasswordController::class, 'verify']);
Route::post('reset/password', [ForgotPasswordController::class, 'resetPassword']);

Route::get('/tentang-kami', function () {
    return view('tentang-kami');
})->name('tentang-kami');

Route::get('search/dataset/{key}', function ($key) {
    $datasets = Dataset::where('name', 'like', '%' . $key . '%')
        ->where('status', 'valid')
        ->get();
    return response()->json($datasets);
});

Route::get('admin/profile', [ProfileController::class, 'profileAdmin'])
    ->middleware('auth')
    ->name('profileAdmin');

Route::get('profil', [ProfileController::class, 'profil'])
    ->middleware('auth')
    ->name('profil');

Route::post('reset-password', [ChangePasswordController::class, 'changePassword'])->middleware('auth');

Route::get('/delete', function () {
    Storage::deleteDirectory('public/datasets/43');
});
