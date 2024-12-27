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

Route::prefix('eventos')->group(function () {
    // Lista todos os eventos
    Route::get('/', [EventoController::class, 'index'])->name('evento.index');
    // Mostra detalhes do evento
    Route::get('/{evento}', [EventoController::class, 'show'])->name('evento.show');
    // Lista as atividades do evento
    Route::get('/{evento}/atividade', [AtividadeController::class, 'index'])->name('atividade.index');
    // Mostra detalhes da atividade
    Route::get('/{evento}/atividade/{id}', [AtividadeController::class, 'show'])->name('atividade.show');
});

// Gerencia evento (precisa estar autenticado e verificado como servidor)
Route::middleware('auth')->group(function(){
    Route::middleware(['verify.ifpr.email'])->group(function () {
        Route::prefix('eventos')->group(function () {
            Route::get('/', [EventoController::class, 'index'])->name('eventos.index');
            Route::get('/criar', [EventoController::class, 'create'])->name('eventos.create');
            Route::post('/store', [EventoController::class, 'store'])->name('eventos.store');
            Route::get('/{evento}/edit', [EventoController::class, 'edit'])->name('evento.edit');
            Route::put('/{evento}', [EventoController::class, 'update'])->name('evento.update');
            Route::delete('/{evento}', [EventoController::class, 'destroy'])->name('evento.destroy');
            Route::post('/{evento}/organizers', [EventoController::class, 'addOrganizador']);
        });
    });
});

// Gerencia atividade
Route::middleware('auth')->group(function(){
    Route::prefix('eventos')->group(function () {
        Route::get('/{evento}/atividade/create', [AtividadeController::class, 'create'])->name('atividade.create');
        Route::post('/{evento}/atividade', [AtividadeController::class, 'store'])->name('atividade.store');
        Route::get('/{evento}/atividade/{id}/edit', [AtividadeController::class, 'edit'])->name('atividade.edit');
        Route::put('/{evento}/atividade/{id}', [AtividadeController::class, 'update'])->name('atividade.update');
        Route::delete('/{evento}/atividade/{id}', [AtividadeController::class, 'destroy'])->name('atividade.destroy');
    });
});

require __DIR__.'/auth.php';
