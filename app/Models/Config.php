<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = 'config';

    protected $fillable = [
        'app_name',
        'logo',
        'favicon',
        'footer_text',
        'contact_email',
        'contact_phone',
        'address',
        'min_deposit_amount',
        'min_withdraw_amount',
        'max_deposit_amount',
        'max_withdraw_amount',
        'description',
        'keywords',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'foreground_color',
        'muted_color',
        'muted_foreground_color',
        'card_color',
        'card_foreground_color',
        'border_color',
        'input_color',
        'ring_color',
        'auto_withdraw_enabled',
        'auto_withdraw_max_amount'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'min_deposit_amount' => 'decimal:2',
        'min_withdraw_amount' => 'decimal:2',
        'max_deposit_amount' => 'decimal:2',
        'max_withdraw_amount' => 'decimal:2',
        'auto_withdraw_enabled' => 'boolean',
        'auto_withdraw_max_amount' => 'decimal:2'
    ];

    /**
     * Get the system configuration
     */
    public static function getSystemConfig()
    {
        return self::first();
    }

    /**
     * Get minimum deposit amount
     */
    public static function getMinDepositAmount()
    {
        $config = self::getSystemConfig();
        return $config->min_deposit_amount ?? 1.00;
    }

    /**
     * Get maximum deposit amount
     */
    public static function getMaxDepositAmount()
    {
        $config = self::getSystemConfig();
        return $config->max_deposit_amount ?? 10000.00;
    }

    /**
     * Get minimum withdraw amount
     */
    public static function getMinWithdrawAmount()
    {
        $config = self::getSystemConfig();
        return $config->min_withdraw_amount ?? 10.00;
    }

    /**
     * Get maximum withdraw amount
     */
    public static function getMaxWithdrawAmount()
    {
        $config = self::getSystemConfig();
        return $config->max_withdraw_amount ?? 50000.00;
    }

    /**
     * Get auto withdraw settings
     */
    public static function getAutoWithdrawSettings(): array
    {
        $config = self::getSystemConfig();
        return [
            'enabled' => $config->auto_withdraw_enabled ?? false,
            'max_amount' => $config->auto_withdraw_max_amount ?? 1000.00
        ];
    }

    /**
     * Get theme colors
     */
    public static function getThemeColors(): array
    {
        $config = self::getSystemConfig();
        return [
            'primary' => $config->primary_color ?? '#4ADE80',
            'secondary' => $config->secondary_color ?? '#1F2937',
            'accent' => $config->accent_color ?? '#6366F1',
            'background' => $config->background_color ?? '#000000',
            'foreground' => $config->foreground_color ?? '#FFFFFF',
            'muted' => $config->muted_color ?? '#374151',
            'muted_foreground' => $config->muted_foreground_color ?? '#9CA3AF',
            'card' => $config->card_color ?? '#111827',
            'card_foreground' => $config->card_foreground_color ?? '#FFFFFF',
            'border' => $config->border_color ?? '#374151',
            'input' => $config->input_color ?? '#374151',
            'ring' => $config->ring_color ?? '#4ADE80'
        ];
    }
} 