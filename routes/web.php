<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;


use App\Http\Livewire\Historico;
use App\Http\Livewire\Administracion;
use App\Http\Livewire\RegistroComponent;
use App\Http\Livewire\RegistroShowComponent;
use App\Http\Livewire\RegistroEditComponent;
use App\Http\Livewire\InicioComponent;

use App\Http\Livewire\MensualComponent;

use App\Http\Livewire\CargarNominaComponent;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware(['auth:sanctum', 'verified'])->get('/', InicioComponent::class)->name('/');
Route::middleware(['auth:sanctum', 'verified'])->get('/historico', Historico::class)->name('/historico');
Route::middleware(['auth:sanctum', 'verified'])->get('/historico/mensual/{mes}/{anio}', MensualComponent::class)->name('/historico/mensual/{mes}/{anio}');
Route::middleware(['auth:sanctum', 'verified'])->get('/historico/show/{id}', RegistroShowComponent::class)->name('/historico/show/{id}');
Route::middleware(['auth:sanctum', 'verified'])->get('/historico/edit/{id}', RegistroEditComponent::class)->name('/historico/edit/{id}');
Route::middleware(['auth:sanctum', 'verified'])->get('/registro', RegistroComponent::class)->name('/registro');
Route::middleware(['auth:sanctum', 'verified'])->get('/administracion', Administracion::class)->name('administracion');

Route::middleware(['auth:sanctum', 'verified'])->get('/nomina', CargarNominaComponent::class)->name('nomina');

// Page Route
//Route::get('/', 'PageController@blankPage');
//Route::get('/page-blank', 'PageController@blankPage');
// Route::get('/page-blank', [PageController::class, 'blankPage']);
// Route::get('/page-collapse', 'PageController@collapsePage');
// Route::get('/testPage', 'ControllerTest@index');

// // locale route
// Route::get('lang/{locale}', [LanguageController::class, 'swap']);

//Auth::routes(['verify' => true]);

//Route::middleware(['auth:sanctum', 'verified'])->get('/', 'PageController@blankPage');
//Route::middleware(['auth:sanctum', 'verified'])->get('registro/edit/{id}', [RegistroComponent::class, 'show']);
// Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
//     return view('dashboard');
// })->name('dashboard');
