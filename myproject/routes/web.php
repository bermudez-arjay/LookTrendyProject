<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Inicio\Inicio;
use App\Livewire\Clients\ClientComponent;
use App\Livewire\Supplier\SupplierComponent;
use App\Livewire\Payments\PaymentComponent;
use App\Livewire\User\UserComponent;
use App\Livewire\InventoryDashboard\InventoryDashboard;
use App\Livewire\Products\ProductComponent;
use App\Livewire\CreditTransaction\CreateCredit;
use Illuminate\Support\Facades\Route;
use App\Livewire\PurchaseTransaction\PurchaseTrasanction;
use App\Livewire\DatabaseBackup\DatabaseBackup;

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

//Pagina de Bienvenida
Route::get("/", function () {
    return view('welcome'); //no tocar
});


// Logout de la aplicación
Route::get('/login', Login::class)->name('login');//no tocar
Route::get('/resetear-Contraseña/{token}', ResetPassword::class)->name('password.reset');
Route::get('/recuperar', ForgotPassword::class)->name('recuperar');
Route::get('/backup', DatabaseBackup::class)->name('backup');
// Grupo de rutas protegidas por autenticación 
Route::middleware(['auth'])->group(function () {

    //Inicio
    Route::get('/inicio', Inicio::class)->name('inicio');

    //compras
    Route::get('/compras', PurchaseTrasanction::class)->name('transaction');

    //Usuarios,Clientes y Proveedores
    Route::get('/usuarios', UserComponent::class)->name('usuarios');
    Route::get('/clientes', ClientComponent::class)->name('clientes');
    Route::get('/proveedores', SupplierComponent::class)->name('proveedores');

    // Productos,Inventario 
    Route::get('/productos', ProductComponent::class)->name('productos');
    Route::get('/inventario', InventoryDashboard::class)->name('inventario');

    //Créditos
    Route::get('/creditos', CreateCredit::class)->name('creditos');
    Route::get('/credits/{credit}', [CreateCredit::class, 'show'])->name('credits.show');

    //Pagos
    Route::get('/payments/{paymentid}/receipt', [PaymentComponent::class, 'receipt'])->name('payments.receipt');
    Route::get('/abonos', PaymentComponent::class)->name('abonos');
});


Route::get('/test-pdf', function() {
    $pdf = Pdf::loadHTML('<h1>Prueba de caracteres: áéíóú ñÑ çÇ</h1>');
    return $pdf->stream();
});


// Modifica la ruta de prueba para ver los últimos 7 días
Route::get('/test-credit-amount', function() {
    return [
        'hoy' => \App\Models\Credit::whereDate('Start_Date', today())->sum('Total_Amount'),
        'ultimos_7_dias' => \App\Models\Credit::whereDate('Start_Date', '>=', now()->subDays(7))
            ->sum('Total_Amount'),
        'total_general' => \App\Models\Credit::sum('Total_Amount')
    ];
});
