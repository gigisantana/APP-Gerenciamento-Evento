<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtividadeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('/evento', 'App\Http\Controllers\EventoController');
Route::get('/report/evento', 'App\Http\Controllers\EventoController@report')->name('report');
// Route::get('graph/evento', 'App\Http\Controllers\EventoController@graph')->name('graph');

Route::resource('/atividades', AtividadeController::class);
// Exibir a listagem de atividades
Route::get('/atividades', [AtividadeController::class, 'index'])->name('atividades.index');

// Mostrar o formulário para criar uma nova atividade
Route::get('/atividades/create', [AtividadeController::class, 'create'])->name('atividades.create');

// Armazenar uma nova atividade
Route::post('/atividades', [AtividadeController::class, 'store'])->name('atividades.store');

// Exibir os detalhes de uma atividade específica
Route::get('/atividades/{id}', [AtividadeController::class, 'show'])->name('atividades.show');

// Mostrar o formulário para editar uma atividade existente
Route::get('/atividades/{id}/edit', [AtividadeController::class, 'edit'])->name('atividades.edit');

// Atualizar uma atividade existente
Route::put('/atividades/{id}', [AtividadeController::class, 'update'])->name('atividades.update');

// Excluir uma atividade existente
Route::delete('/atividades/{id}', [AtividadeController::class, 'destroy'])->name('atividades.destroy');

require __DIR__.'/auth.php';
