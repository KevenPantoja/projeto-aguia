<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadeSaude extends Model
{
    use HasFactory;

    // Definir o nome da tabela
    protected $table = 'tb_cnes_dist';  

    // Definir a chave primária
    protected $primaryKey = 'nu_cnes'; // Definindo 'nu_cnes' como chave primária

    // Se não houver timestamps, desabilite a opção
    public $timestamps = false;

    // Campos preenchíveis
    protected $fillable = ['no_unidade', 'no_disa', 'nu_cnes'];

    // Relacionamento com Distrito (se necessário)
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'no_disa', 'no_disa');
    }
}
