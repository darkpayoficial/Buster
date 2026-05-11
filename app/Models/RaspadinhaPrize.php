<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RaspadinhaPrize extends Model
{
    use HasFactory;

    protected $table = 'raspadinha_prizes';

    protected $fillable = [
        'raspadinha_id',
        'name',
        'value',
        'probability',
        'display_value',
        'img',
        'is_jackpot',
        'max_wins',
        'current_wins',
        'active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'probability' => 'decimal:2',
        'is_jackpot' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * Accessor para garantir que o caminho da imagem sempre tenha a barra inicial
     */
    public function getImgAttribute($value)
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
     * Relacionamento com a raspadinha
     */
    public function raspadinha(): BelongsTo
    {
        return $this->belongsTo(Raspadinha::class);
    }

    /**
     * Scope para prêmios ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para prêmios disponíveis (que não atingiram o limite)
     */
    public function scopeAvailable($query)
    {
        return $query->where('active', true)
                    ->where(function($q) {
                        $q->whereNull('max_wins')
                          ->orWhereRaw('current_wins < max_wins');
                    });
    }

    /**
     * Scope para prêmios jackpot
     */
    public function scopeJackpot($query)
    {
        return $query->where('is_jackpot', true);
    }

    /**
     * Verifica se o prêmio ainda pode ser ganho
     */
    public function canBeWon(): bool
    {
        if (!$this->active) {
            return false;
        }

        if ($this->max_wins && $this->current_wins >= $this->max_wins) {
            return false;
        }

        return true;
    }

    /**
     * Incrementa o número de vezes que foi ganho
     */
    public function incrementWins(): void
    {
        $this->increment('current_wins');
    }

    /**
     * Calcula o valor formatado
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->value == 0) {
            return 'R$ 0,00';
        }
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * Calcula a probabilidade formatada
     */
    public function getFormattedProbabilityAttribute(): string
    {
        return number_format($this->probability, 0) . '%';
    }

    /**
     * Verifica se é um prêmio de "nada"
     */
    public function isNothingPrize(): bool
    {
        return $this->value == 0;
    }

    /**
     * Calcula quantas vezes ainda pode ser ganho
     */
    public function getRemainingWinsAttribute(): ?int
    {
        if (!$this->max_wins) {
            return null;
        }

        return max(0, $this->max_wins - $this->current_wins);
    }

    /**
     * Verifica se o prêmio está esgotado
     */
    public function isSoldOut(): bool
    {
        return $this->max_wins && $this->current_wins >= $this->max_wins;
    }

    /**
     * Retorna o valor de exibição ou o valor formatado
     */
    public function getDisplayValueAttribute(): string
    {
        return $this->attributes['display_value'] ?? $this->formatted_value;
    }

    /**
     * Validação automática para garantir que as probabilidades não excedam 100%
     */
    public static function validateTotalProbability(int $raspadinhaId, float $newProbability, ?int $excludeId = null): bool
    {
        $query = self::where('raspadinha_id', $raspadinhaId)
                     ->where('active', true);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $totalProbability = $query->sum('probability');
        
        return ($totalProbability + $newProbability) <= 100;
    }

    /**
     * Cria prêmios padrão para uma raspadinha
     */
    public static function createDefaultPrizes(int $raspadinhaId): void
    {
        $defaultPrizes = [
            [
                'name' => 'Nada',
                'value' => 0,
                'probability' => 50,
                'display_value' => 'R$ 0,00',
                'is_jackpot' => false,
            ],
            [
                'name' => 'R$ 1,00',
                'value' => 1,
                'probability' => 20,
                'display_value' => 'R$ 1,00',
                'is_jackpot' => false,
            ],
            [
                'name' => 'R$ 5,00',
                'value' => 5,
                'probability' => 20,
                'display_value' => 'R$ 5,00',
                'is_jackpot' => false,
            ],
            [
                'name' => 'R$ 20,00',
                'value' => 20,
                'probability' => 10,
                'display_value' => 'R$ 20,00',
                'is_jackpot' => true,
            ],
        ];

        foreach ($defaultPrizes as $prize) {
            self::create(array_merge($prize, [
                'raspadinha_id' => $raspadinhaId,
                'active' => true,
            ]));
        }
    }
}
