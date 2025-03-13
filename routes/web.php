<?php

use App\Http\Controllers\Admin\FiveWordController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SevenWordController;
use App\Http\Controllers\Admin\SixWordController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\ThreeWordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Imports\ThreeWordImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect('super_admin');
});

Route::prefix('super_admin')->as('super_admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/change', [HomeController::class, 'change'])->name('change');
    Route::post('/updatePassword', [HomeController::class, 'updatePassword'])->name('password.update');
    Route::prefix('threeword')->as('threeword.')->group(function () {
        Route::get('/', [ThreeWordController::class, 'index'])->name('index');
        Route::post('store', [ThreeWordController::class, 'store'])->name('store');
        Route::post('update/{id}', [ThreeWordController::class, 'update'])->name('update');
        Route::post('delete', [ThreeWordController::class, 'delete'])->name('delete');
        Route::post('/import-threeword', [ThreeWordController::class, 'importThreeWord'])->name('import');
    });

    Route::prefix('fiveword')->as('fiveword.')->group(function () {
        Route::get('/', [FiveWordController::class, 'index'])->name('index');
        Route::post('store', [FiveWordController::class, 'store'])->name('store');
        Route::post('update/{id}', [FiveWordController::class, 'update'])->name('update');
        Route::post('delete', [FiveWordController::class, 'delete'])->name('delete');
        Route::post('/import-fiveword', [FiveWordController::class, 'importFiveWord'])->name('import');

    });

    Route::prefix('sevenword')->as('sevenword.')->group(function () {
        Route::get('/', [SevenWordController::class, 'index'])->name('index');
        Route::post('store', [SevenWordController::class, 'store'])->name('store');
        Route::post('update/{id}', [SevenWordController::class, 'update'])->name('update');
        Route::post('delete', [SevenWordController::class, 'delete'])->name('delete');
        Route::post('/import-sevenword', [SevenWordController::class, 'importSevenWord'])->name('import');
    });
    Route::prefix('theme')->as('theme.')->group(function () {
        Route::get('/', [ThemeController::class, 'index'])->name('index');
        Route::post('store', [ThemeController::class, 'store'])->name('store');
        Route::post('update/{id}', [ThemeController::class, 'update'])->name('update');
        Route::post('delete', [ThemeController::class, 'delete'])->name('delete');
        Route::get('/get-last-theme-date', [ThemeController::class, 'getLastThemeDate'])->name('getLastThemeDate');


    });
    
});





// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
