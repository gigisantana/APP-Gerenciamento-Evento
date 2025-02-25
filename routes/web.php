<?php

use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LocalController;
use App\Models\Registro;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::get('/home', [EventoController::class, 'eventosProximos'])->name('home');
//Route::get('/locais/{bloco}', [LocalController::class, 'getEspacos'])->name('locais.espacos');


Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/sobre', function () {
    return view('sobre');
})->name('sobre');


// Gerencia evento (precisa estar autenticado e verificado como servidor)
Route::middleware('auth', 'verified')->group(function(){
    Route::middleware(['verify.ifpr.email'])->group(function () {
        Route::prefix('eventos')->group(function () {
            Route::get('/criar', [EventoController::class, 'create'])->name('evento.create');
            Route::get('/{id}/edit', [EventoController::class, 'edit'])->name('evento.edit');
            Route::post('/{id}/organizadores', [RegistroController::class, 'addOrganizador'])->name('registro.vincular');
        });
    });
});
Route::patch('/{id}', [EventoController::class, 'update'])->name('evento.update');
Route::delete('/{id}', [EventoController::class, 'destroy'])->name('evento.destroy');
Route::post('/', [EventoController::class, 'store'])->name('evento.store');

// Gerencia atividade (precisa estar autenticado e vinculado ao evento)
Route::middleware('auth', 'verified')->group(function(){
    Route::prefix('eventos')->group(function () {
        Route::middleware(['verify.role.evento'])->group(function () {
            Route::get('/{id}/create', [AtividadeController::class, 'create'])->name('atividade.create');
            Route::post('/{id}', [AtividadeController::class, 'store'])->name('atividade.store');
            Route::get('/{id}/atividades/{atividade_id}/edit', [AtividadeController::class, 'edit'])->name('atividade.edit');
            Route::patch('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'update'])->name('atividade.update');
            Route::delete('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'destroy'])->name('atividade.destroy');
            
            Route::get('/{id}/atividades/{atividade_id}/inscritos', [RegistroController::class, 'inscritos'])->name('atividade.inscritos');
            Route::get('/{id}/atividades/{atividade_id}/relatorio/pdf', [RegistroController::class, 'exportarPDF'])->name('relatorio.pdf');
        });
    });
});

// Gerencia perfil
Route::middleware('auth', 'verified')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/editar', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/editar', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/editar', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/inscricoes', [ProfileController::class, 'inscricoes'])->name('profile.inscricoes');
        Route::get('/gerenciar', [ProfileController::class, 'gerenciar'])->name('profile.gerenciamento');
    });
    // Inscrição em atividade
    Route::post('/eventos/{id}/atividades/{atividade_id}/inscricao', [RegistroController::class, 'inscrever'])->name('registro.inscrever');
});

Route::prefix('eventos')->group(function () {
    // Lista todos os eventos
    Route::get('/', [EventoController::class, 'index'])->name('evento.index');
    // Mostra detalhes do evento
    Route::get('/{id}', [EventoController::class, 'show'])->name('evento.show');
    // Lista as atividades do evento
    Route::get('/{id}/atividades', [AtividadeController::class, 'index'])->name('atividade.index');
    // Mostra detalhes da atividade
    Route::get('/{id}/atividades/{atividade_id}', [AtividadeController::class, 'show'])->name('atividade.show');
});



require __DIR__.'/auth.php';