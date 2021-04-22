<?php

//use App\Http\Controllers\LanguageController;
//use App\Http\Controllers\PageController;

use App\Http\Livewire\Historico;
use App\Http\Livewire\Administracion;
use App\Http\Livewire\InicioComponent;
use App\Http\Livewire\MensualComponent;
use App\Http\Livewire\RegistroComponent;
use App\Http\Livewire\RegistroShowComponent;
use App\Http\Livewire\RegistroEditComponent;
use App\Http\Livewire\NominaComponent;
use App\Http\Livewire\NominaResumenComponent;

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
Route::middleware(['auth:sanctum', 'verified'])->get('/administracion', Administracion::class)->name('administracion');

Route::middleware(['auth:sanctum', 'verified'])->get('/historico', Historico::class)->name('/historico');
Route::middleware(['auth:sanctum', 'verified'])->get('/historico/mensual/{mes}/{anio}', MensualComponent::class)->name('/historico/mensual/{mes}/{anio}');
Route::middleware(['auth:sanctum', 'verified'])->get('/historico/show/{id}', RegistroShowComponent::class)->name('/historico/show/{id}');
Route::middleware(['auth:sanctum', 'verified'])->get('/historico/edit/{id}', RegistroEditComponent::class)->name('/historico/edit/{id}');
Route::middleware(['auth:sanctum', 'verified'])->get('/registro', RegistroComponent::class)->name('/registro');

Route::middleware(['auth:sanctum', 'verified'])->get('/nomina', NominaComponent::class)->name('nomina');
Route::middleware(['auth:sanctum', 'verified'])->get('/nomina/resumen/{quincena}/{mes}/{anio}', NominaResumenComponent::class)->name('nomina/resumen/{quincena}/{mes}/{anio}');
Route::middleware(['auth:sanctum', 'verified'])->get('/nomina/resumen/pdf/{quincena}/{mes}/{anio}', [NominaResumenComponent::class, 'pdf']);