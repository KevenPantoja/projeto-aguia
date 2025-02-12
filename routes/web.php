<?php

use App\Http\Controllers\ObitoController;
use App\Http\Controllers\NotificacaoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\DetalhesDesatualizadosController;
use App\Http\Controllers\AtendimentoController;

Route::get('/obitos/notificacoes', [NotificacaoController::class, 'index'])->name('obitos.notificacoes.index');
Route::get('/obitos/notificacoes/datatables', [NotificacaoController::class, 'getDatatable'])->name('obitos.notificacoes.datatable');
Route::get('/', [ObitoController::class, 'index']);
Route::get('/obitos', [ObitoController::class, 'index']);
Route::get('/obitos/nome-lista', [ObitoController::class, 'listaNomes'])->name('obitos.listaNomes');


Route::get('/obitos', [DistritoController::class, 'index'])->name('obitos.index');
Route::get('/obitos/distrito', [DistritoController::class, 'index'])->name('obitos.distrito');


Route::get('/detalhes-desatualizados', [DistritoController::class, 'distrito'])->name('obitos.detalhes_desatualizados');

Route::get('/unidades-saude', [DistritoController::class, 'getUnidadesSaude']);



Route::get('/atendimentos', [AtendimentoController::class, 'index'])->name('atendimentos');
Route::get('/detalhes-atendimentos-pos-obitos', [AtendimentoController::class, 'detalhesAtendimentosPosObitos'])->name('detalhes.atendimentos.pos.obitos');
