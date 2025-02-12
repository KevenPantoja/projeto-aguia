<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DadosDesatualizados extends Model
{
    use HasFactory;

    // Nome da tabela no banco de dados
    protected $table = 'tb_dados_desatualizados';

    // Definindo os campos que podem ser preenchidos
    protected $fillable = [
        'no_cidadao',
        'dt_nascimento',
        'no_mae',
        'nu_cns',
        'nu_cpf',
        'nu_cnes',
        'no_unidade_saude',
        'dias_atraso',
    ];

    // Caso queira ajustar a data, adicione a configuração de data
    protected $dates = [
        'dt_nascimento',
    ];
}
