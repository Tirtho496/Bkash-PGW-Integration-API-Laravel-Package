<?php

use Tirtho496\Bkash_pgw\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('payment', PaymentController::class);
Route::get('/bkash/pay', [Payment::class, 'payment'])->name('');
Route::post('/bkash/create', [Payment::class, 'createPayment'])->name('');
Route::get('/bkash/callback', [Payment::class, 'callback'])->name('');
Route::post('/bkash/search', [Payment::class, 'searchPayment'])->name('');
Route::get('/bkash/executeCallback', [Payment::class, 'executeCallback'])->name('');
Route::post('/bkash/createAgreement', [Payment::class, 'createAgreement'])->name('');
Route::post('/bkash/payAgreement', [Payment::class, 'payAgreement'])->name('');