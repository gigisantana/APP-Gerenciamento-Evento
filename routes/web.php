<?php

use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistroController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventoController::class, 'eventosProximos'])->name('dashboard');
Route::get('/', [EventoController::class, 'eventosProximos'])->name('dashboard');

Route::get('/sobre', function () {
    return view('sobre');
})->name('sobre');

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

// Gerencia perfil
Route::middleware('auth', 'verified')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/editar', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/editar', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/editar', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/inscricoes', [ProfileController::class, 'inscricoes'])->name('profile.inscricoes');
    });
    // Inscrição em atividade
    Route::post('/eventos/{id}/atividades/{atividade_id}/inscricao', [RegistroController::class, 'inscrever'])->name('registro.inscrever');
});

Route::prefix('eventos')->group(function () {
    // Lista todos os eventos
    Route::get('/', [EventoController::class, 'index'])->name('evento.index');
    // Mostra detalhes do evento
    Route::get('/{id}', [EventoController::class, 'show'])->name('evento.show');
    Route::get('/{id}', [EventoController::class, 'show'])->name('evento.show');
    // Lista as atividades do evento
    Route::get('/{id}/atividades', [AtividadeController::class, 'index'])->name('atividade.index');
    Route::get('/{id}/atividades', [AtividadeController::class, 'index'])->name('atividade.index');
    // Mostra detalhes da atividade
    Route::get('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'show'])->name('atividade.show');
    Route::get('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'show'])->name('atividade.show');
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
            Route::post('/{evento}/organizadores', [EventoController::class, 'addOrganizador']);
        });
    });
});

// Gerencia atividade (precisa estar autenticado e vinculado ao evento)
Route::middleware('auth', 'verified')->group(function(){
Route::middleware('auth', 'verified')->group(function(){
    Route::prefix('eventos')->group(function () {
        Route::get('/{id}/atividades/create', [AtividadeController::class, 'create'])->name('atividade.create');
        Route::post('/{id}/atividades', [AtividadeController::class, 'store'])->name('atividade.store');
        Route::get('/{id}/atividades/{atividade_id}/edit', [AtividadeController::class, 'edit'])->name('atividade.edit');
        Route::put('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'update'])->name('atividade.update');
        Route::delete('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'destroy'])->name('atividade.destroy');
        Route::get('/{id}/atividades/create', [AtividadeController::class, 'create'])->name('atividade.create');
        Route::post('/{id}/atividades', [AtividadeController::class, 'store'])->name('atividade.store');
        Route::get('/{id}/atividades/{atividade_id}/edit', [AtividadeController::class, 'edit'])->name('atividade.edit');
        Route::put('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'update'])->name('atividade.update');
        Route::delete('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'destroy'])->name('atividade.destroy');
    });
});

require __DIR__.'/auth.php';
