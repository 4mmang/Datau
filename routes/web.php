<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KelolaArtikelController;
use App\Http\Controllers\Admin\KelolaDatasetController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ValidasiDatasetController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ContributeDatasetController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginGithubController;
use App\Http\Controllers\LoginGoogleController;
use App\Http\Controllers\RegistrationController;
use App\Models\Article;
use App\Models\Dataset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// beranda
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// authentikasi
Route::get('login', [AuthController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('login/validation', [AuthController::class, 'validation']);

// logout
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// registrasi
Route::get('register', [RegistrationController::class, 'index'])->middleware('guest');
Route::post('register/user', [RegistrationController::class, 'store']);

// verifikasi email
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
    ->middleware(['auth', 'throttle:1,1'])
    ->name('verification.send');

// login with google
Route::get('/auth/google/redirect', [LoginGoogleController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [LoginGoogleController::class, 'googleCallback']);

// login with github
Route::get('/auth/github/redirect', [LoginGithubController::class, 'githubRedirect']);
Route::get('/auth/github/callback', [LoginGithubController::class, 'githubCallback']);

// download dataset
Route::get('download/{id}', [DownloadController::class, 'download'])->middleware(['auth', 'verified']);

// dataset
Route::prefix('/dataset')
    ->as('dataset.')
    ->group(function () {
        Route::group(['middleware' => ['auth', 'verified']], function () {
            Route::get('/create', [ContributeDatasetController::class, 'create'])->name('create');
            Route::post('/store', [ContributeDatasetController::class, 'store'])->name('store');
        });
        Route::get('/', [DatasetController::class, 'index'])->name('index');
        Route::get('/{id}', [DatasetController::class, 'show'])->name('show');
        Route::get('filter/{id}', [DatasetController::class, 'filter'])->name('filter');
        
    });

// sumbang paper
Route::post('donation/paper', [PaperController::class, 'store'])->middleware(['auth', 'verified'])->name('upload-paper');

// admin
Route::prefix('/admin')
    ->as('admin.')
    ->group(function () {
        Route::group(['middleware' => ['auth', 'verified']], function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::resource('/dataset', KelolaDatasetController::class);
            Route::get('/profil', [ProfilController::class, 'index'])
            ->name('profil');
        });


        Route::group(['middleware' => ['auth', 'verified', 'role:admin']], function () {
            Route::put('/validate/dataset/{id}', [ValidasiDatasetController::class, 'valid']);
            Route::post('/invalid/dataset/{id}', [ValidasiDatasetController::class, 'invalid']);
            Route::resource('/user', UserController::class);
            Route::resource('/artikel', KelolaArtikelController::class);
        });
    });

// detail artikel
Route::resource('/artikel', ArtikelController::class);

// reset password
Route::get('forgot/password', function () {
    return view('auth.forgot-password');
})->middleware('guest');
Route::post('send/code/verification', [ForgotPasswordController::class, 'sendCodeVerification']);
Route::post('verify', [ForgotPasswordController::class, 'verify']);
Route::post('reset/password', [ForgotPasswordController::class, 'resetPassword']);

// tentang kami
Route::get('/tentang-kami', function () {
    return view('tentang-kami');
})->name('tentang-kami');

// pencarian dataset
Route::get('search/dataset/{key}', function ($key) {
    $datasets = Dataset::where('name', 'like', '%' . $key . '%')
        ->where('status', 'valid')
        ->get();
    return response()->json($datasets);
});

// ganti password
Route::post('reset-password', [ChangePasswordController::class, 'changePassword'])->middleware('auth');
