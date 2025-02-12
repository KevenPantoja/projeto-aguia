<?php

// app/Models/NotificacaoSinan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacaoSinan extends Model
{
    use HasFactory;

    // Nome da tabela no banco de dados
    protected $table = 'tb_dados_notificacoes_sinan';  // Nome da tabela no banco de dados

    protected $fillable = ['tipo_agravo', 'ano_notific', 'mes_notific', 'quant_notific'];
    
    // Caso o nome das colunas de data sejam diferentes
    // protected $dates = ['created_at', 'updated_at']; 
}
