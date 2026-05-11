<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltimosGanhos extends Model
{
    use HasFactory;

    protected $table = 'ultimos_ganhos';

    protected $fillable = [
        'namewin',
        'prizename', 
        'valueprize',
        'imgprize',
        'active'
    ];

    protected $casts = [
        'valueprize' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
