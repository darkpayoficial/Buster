<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Raspadinha extends Model
{
    use HasFactory;

    protected $table = 'raspadinhas';

    protected $fillable = [
        'name',
        'photo',
        'title',
        'description',
        'totalbuy',
        'value',
        'active',
        'hot',
        'max_sales',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'active' => 'boolean',
        'hot' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Accessor para garantir que o caminho da foto sempre tenha a barra inicial
     */
    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        if (str_starts_with($value, '/')) {
            return $value;
        }
        
        return '/' . $value;
    }

    /**
     * Relacionamento com os prêmios da raspadinha
     */
    public function prizes(): HasMany
    {
        return $this->hasMany(RaspadinhaPrize::class);
    }

    /**
     * Relacionamento com os prêmios ativos
     */
    public function activePrizes(): HasMany
    {
        return $this->hasMany(RaspadinhaPrize::class)->where('active', true);
    }

    /**
     * Scope para raspadinhas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para raspadinhas hot (destaques)
     */
    public function scopeHot($query)
    {
        return $query->where('hot', true);
    }

    /**
     * Scope para raspadinhas disponíveis para venda
     */
    public function scopeAvailable($query)
    {
        return $query->where('active', true)
                    ->where(function($q) {
                        $q->whereNull('max_sales')
                          ->orWhereRaw('totalbuy < max_sales');
                    })
                    ->where(function($q) {
                        $q->whereNull('start_date')
                          ->orWhere('start_date', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    /**
     * Verifica se a raspadinha pode ser vendida
     */
    public function canBeSold(): bool
    {
        if (!$this->active) {
            return false;
        }

        if ($this->max_sales && $this->totalbuy >= $this->max_sales) {
            return false;
        }

        if ($this->start_date && $this->start_date > now()) {
            return false;
        }

        if ($this->end_date && $this->end_date < now()) {
            return false;
        }

        return true;
    }

    /**
     * Incrementa o total de vendas
     */
    public function incrementSales(): void
    {
        $this->increment('totalbuy');
    }

    /**
     * Calcula o valor formatado
     */
    public function getFormattedValueAttribute(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * Calcula quantas raspadinhas ainda podem ser vendidas
     */
    public function getRemainingAttribute(): ?int
    {
        if (!$this->max_sales) {
            return null;
        }

        return max(0, $this->max_sales - $this->totalbuy);
    }

    /**
     * Verifica se a raspadinha está esgotada
     */
    public function isSoldOut(): bool
    {
        return $this->max_sales && $this->totalbuy >= $this->max_sales;
    }

    /**
     * Pega um prêmio baseado nas probabilidades
     */
    public function drawPrize(): ?RaspadinhaPrize
    {
        $prizes = $this->activePrizes()->get();
        
        if ($prizes->isEmpty()) {
            return null;
        }

        $availablePrizes = $prizes->filter(function ($prize) {
            return !$prize->max_wins || $prize->current_wins < $prize->max_wins;
        });

        if ($availablePrizes->isEmpty()) {
            return null;
        }

        $totalProbability = $availablePrizes->sum('probability');
        
        $random = mt_rand(1, $totalProbability * 100) / 100;
        
        $cumulativeProbability = 0;
        
        foreach ($availablePrizes as $prize) {
            $cumulativeProbability += $prize->probability;
            
            if ($random <= $cumulativeProbability) {
                return $prize;
            }
        }

        return $availablePrizes->last();
    }
}
