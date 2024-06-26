<?php

use App\Http\Controllers\{Auth\Github, DashboardController, ProfileController, Question, QuestionController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //    if (app()->isLocal()) {
    //        auth()->loginUsingId(1);
    //
    //        return redirect()->route('dashboard');
    //    }

    return view('welcome');
});
Route::get('github/login', Github\RedirectController::class)->name('github.login');
Route::get('github/callback', Github\CallbackController::class)->name('github.callback');
Route::middleware(['auth', 'verified'])->group(function () {

    //
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    //region Question Routes
    Route::prefix('question')->name('question.')->group(function () {
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::post('/store', [QuestionController::class, 'store'])->name('store');
        Route::delete('/delete/{question}', [QuestionController::class, 'destroy'])->name('destroy');
        Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
        Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
        Route::patch('/archrive/{question}', [QuestionController::class, 'archive'])->name('archive');
        Route::patch('/restore/{question}', [QuestionController::class, 'restore'])->name('restore');
        Route::post('/like/{question}', Question\LikeController::class)->name('like');
        Route::post('/unlike/{question}', Question\UnlikeController::class)->name('unlike');
        Route::put('/publish/{question}', Question\PublishController::class)->name('publish');

    });
    //endregion

    //region Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //endregion
});

require __DIR__ . '/auth.php';
