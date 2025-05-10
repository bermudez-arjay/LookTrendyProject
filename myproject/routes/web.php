<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword; 
use App\Livewire\Auth\ResetPassword; 
use App\Livewire\Clients\Clients;
use App\Livewire\Inicio\Inicio;
use App\Livewire\Clients\ClientComponent;
use App\Livewire\Payments\PaymentComponent;
use App\Livewire\User\UserComponent;
use App\Livewire\Products\ProductComponent;
use App\Livewire\CreditTransaction\CreateCredit;
use Illuminate\Support\Facades\Route;
use App\Livewire\PurchaseTransaction\PurchaseTrasanction;

use Illuminate\Support\Facades\Session;
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

Route::get("/", function () {
    return view('welcome'); //no tocar
});
// Route::middleware(['auth'])->group(function () {
    Route::get('/usuarios', UserComponent::class)->name('usuarios');
   
// });
// Ruta de login
Route::get('/login', Login::class)->name('login');//no tocar

Route::get('/transaction', \App\Livewire\PurchaseTransaction\PurchaseTrasanction::class)->name('transaction');//no tocar
// Grupo de rutas protegidas por autenticación 
Route::middleware(['auth'])->group(function () {
    Route::get('/inicio', Inicio::class)->name('inicio');
   
});

Route::middleware(['auth'])->group(function () {
    Route::get('/clientes', ClientComponent::class)->name('clientes');
   
});
Route::middleware(['auth'])->group(function () {
    Route::get('/productos', ProductComponent::class)->name('productos');
   
});
Route::middleware(['auth'])->group(function () {
    Route::get('/creditos', CreateCredit::class)->name('creditos');
   
});
Route::middleware(['auth'])->group(function () {
    Route::get('/abonos', PaymentComponent::class)->name('abonos');
   
});

Route::get('/recuperar', function () {
    return view('forgot-password');
})->name('recuperar');


Route::get('/reset-password/{token}', ResetPassword::class)
    ->middleware('guest')
    ->name('password.reset');