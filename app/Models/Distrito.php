<?php

// app/Models/Distrito.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    use HasFactory;

    // Definindo o nome da tabela
    protected $table = 'tb_cnes_dist';

    // Definindo as colunas relacionadas ao Distrito
    protected $primaryKey = 'id'; // Defina a chave primária se necessário

    // Definindo os campos que podem ser preenchidos
    protected $fillable = ['no_disa'];

    // Se você tiver outra conexão com o banco, defina assim:
    // protected $connection = 'nome_da_conexao';
}
