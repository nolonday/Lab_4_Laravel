<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

Route::get('/signin', [AuthController::class, 'create'])->name('signin');
Route::post('/signin', [AuthController::class, 'registration'])->name('signin.registration');

Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/galery/{article}', [MainController::class, 'galery'])
    ->whereNumber('article')
    ->name('galery');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contacts', function () {

    $contacts = [
        [
            'name' => 'Соловьев Даниил Дмитриевич',
            'position' => 'Студент, группа 257-321',
            'email' => 'student@example.com',
            'phone' => '+7 (900) 000-00-00',
        ],
        [
            'name' => 'Учебная часть',
            'position' => 'По учебным вопросам',
            'email' => 'study@example.com',
            'phone' => '+7 (900) 111-11-11',
        ],
    ];

    return view('pages.contacts', [
        'contacts' => $contacts,
    ]);

})->name('contacts');
