<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtividadeController;
use Illuminate\Support\Facades\Mail;

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
    return view('home');
})->name('home');

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

// Rota para login
Route::get('/login', function(){
    return view('login');
})->name('login');

// Rota para registro
Route::get('/profile/register', [ProfileController::class, 'showRegistrationForm'])->name('profile.register');
Route::put('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// Rota para a página de descrição do projeto
Route::get('sobre', function(){
    return view('sobre');
})->name('sobre');

Route::get('/send-test-email', function () {
    Mail::raw('Este é um teste de envio de e-mail via Mailtrap.', function ($message) {
        $message->to('destinatario@exemplo.com')
                ->subject('Teste de E-mail');
    });
    return 'E-mail enviado!';
});


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
require __DIR__.'/auth.php';
