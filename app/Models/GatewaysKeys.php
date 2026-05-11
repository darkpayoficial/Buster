<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewaysKeys extends Model
{
    use HasFactory;

    protected $table = 'gatewayskeys';

    protected $fillable = [
        'primebank_client_id',
        'primebank_client_secret',
    ];

    protected $hidden = [
    ];

    /**
     * Obter as configurações do gateway (singleton)
     */
    public static function getKeys()
    {
        return self::first() ?? self::create([
            'primebank_client_id' => null,
            'primebank_client_secret' => null,
        ]);
    }

    /**
     * Verificar se as chaves do PrimeBank estão configuradas
     */
    public function isPrimeBankConfigured(): bool
    {
        return !empty($this->primebank_client_id) && !empty($this->primebank_client_secret);
    }
} 