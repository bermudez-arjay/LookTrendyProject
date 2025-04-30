<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword; 
use App\Livewire\Auth\ResetPassword; 
use App\Livewire\Clients\Clients;
use App\Livewire\Inicio\Inicio;
use App\Livewire\Clients\ClientComponent;
use App\Livewire\User\UserComponent;
use Illuminate\Support\Facades\Route;
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
Route::get('/usuarios',UserComponent::class)->name('usuarios');//no tocar
Route::get('/clientes',ClientComponent::class)->name('clientes');
// Ruta de login
Route::get('/login', Login::class)->name('login');//no tocar


Route::get('/recuperar', ForgotPassword::class)->name('recuperar');

Route::get('/reset-password', ResetPassword::class)->name('reset-password');
Route::get('/transaction', \App\Livewire\PurchaseTransaction\PurchaseTrasanction::class)->name('transaction');//no tocar
// Grupo de rutas protegidas por autenticación 
Route::middleware(['auth'])->group(function () {
    Route::get('/inicio', Inicio::class)->name('inicio');
   
});







