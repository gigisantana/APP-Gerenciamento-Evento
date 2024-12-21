<?php

use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Lista todos os eventos
Route::get('/evento', [EventoController::class, 'index'])->name('evento.index');
// Mostra detalhes do evento
Route::get('/evento/{id}', [EventoController::class, 'show'])->name('evento.show');
// Lista as atividades do evento
Route::get('/evento/{eventoId}/atividade', [AtividadeController::class, 'index'])->name('atividade.index');
// Mostra detalhes da atividade
Route::get('/evento/{eventoId}/atividade/{id}', [AtividadeController::class, 'show'])->name('atividade.show');


// Gerencia evento (precisa estar autenticado)
Route::middleware('auth')->group(function(){
    Route::get('/evento/create', [EventoController::class, 'create'])->name('evento.create');
    Route::post('/evento', [EventoController::class, 'store'])->name('evento.store');
    Route::get('/evento/{id}/edit', [EventoController::class, 'edit'])->name('evento.edit');
    Route::put('/evento/{id}', [EventoController::class, 'update'])->name('evento.update');
    Route::delete('/evento/{id}', [EventoController::class, 'destroy'])->name('evento.destroy');
});

// Gerencia atividade
Route::middleware('auth')->group(function(){
    Route::get('/evento/{eventoId}/atividade/create', [AtividadeController::class, 'create'])->name('atividade.create');
    Route::post('/evento/{eventoId}/atividade', [AtividadeController::class, 'store'])->name('atividade.store');
    Route::get('/evento/{eventoId}/atividade/{id}/edit', [AtividadeController::class, 'edit'])->name('atividade.edit');
    Route::put('/evento/{eventoId}/atividade/{id}', [AtividadeController::class, 'update'])->name('atividade.update');
    Route::delete('/evento/{eventoId}/atividade/{id}', [AtividadeController::class, 'destroy'])->name('atividade.destroy');
});

require __DIR__.'/auth.php';
