<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JogoHistorico extends Model
{
    use HasFactory;

    protected $table = 'jogos_historico';

    protected $fillable = [
        'user_id',
        'raspadinha_id',
        'raspadinha_name',
        'prize_id',
        'prize_name',
        'prize_value',
        'prize_img',
        'status', // 'win' ou 'loss'
    ];

    protected $casts = [
        'prize_value' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function raspadinha()
    {
        return $this->belongsTo(Raspadinha::class);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
} 