<?php

use App\Http\Controllers\DatamatrixController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\TypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'auth'], function () {

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::apiResources([
        'places' => PlaceController::class,
        'sizes' => SizeController::class,
        'types' => TypeController::class,
        'infos' => InfoController::class,
        'orders' => OrderController::class,
        'datamatrix' => DatamatrixController::class
    ]);

    Route::get('roles', [RoleController::class, 'index']);
    Route::get('seasons', [SeasonController::class, 'index']);

    Route::post('logout', [UserController::class, 'logout']);

    Route::post('users', [UserController::class, 'store']);
    Route::get('users', [UserController::class, 'users']);
    Route::patch('users/{user}', [UserController::class, 'update']);

    Route::get('abilities', function (Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});
