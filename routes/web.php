<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Eco35Controller;

Route::get('/', [Eco35Controller::class, 'index'])->name('eco35.index');
Route::post('/siembras', [Eco35Controller::class, 'guardarSiembra'])->name('siembras.store');
Route::post('/alertas', [Eco35Controller::class, 'guardarAlerta'])->name('alertas.store');
Route::delete('/alertas/{id}', [Eco35Controller::class, 'eliminarAlerta'])->name('alertas.destroy');
    