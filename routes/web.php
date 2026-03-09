<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('/reset-password/{token}', function (Request $request, string $token) {
	return response()->json([
		'message' => 'Use this token and email to call POST /api/reset-password.',
		'token' => $token,
		'email' => $request->query('email'),
	]);
})->name('password.reset');
