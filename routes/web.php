<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/order', function () {
    $pdf = Pdf::loadView('pdf.order', [
        'orderId' => 1,
        'employee' => 'Иванов Иван',
        'service' => 'шиномонтаж',
        'count' => 2,
        'price' => 200,
        'name' => 'Tunga Nord Way 2',
        'address' => 'г. Минск, ул. Промышленная, 2б'
    ]);
    return $pdf->stream();
});
