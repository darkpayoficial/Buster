<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Deposit;
use App\Models\Withdrawal;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'nomecompleto',
        'document',
        'telefone',
        'balance',
        'pix_key',
        'pix_key_type',
        'referral_code',
        'referral_level',
        'referral_xp',
        'referral_commission',
        'commission_balance',
        'referred_by',
        'is_influencer'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'bloqueado' => 'boolean',
            'is_influencer' => 'boolean',
            'last_login' => 'datetime',
            'last_logout' => 'datetime',
            'total_deposit' => 'decimal:2',
            'total_withdraw' => 'decimal:2',
            'total_cashback' => 'decimal:2',
            'balance' => 'decimal:2',
        ];
    }

    /**
     * Constants para roles
     */
    const ROLE_USER = 'USER';
    const ROLE_ADMIN = 'ADMIN';

    /**
     * Verificar se o usuário é admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Verificar se o usuário é user comum
     */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Verificar se a conta está bloqueada
     */
    public function isBlocked(): bool
    {
        return $this->bloqueado;
    }

    /**
     * Verificar se o usuário é influenciador
     */
    public function isInfluencer(): bool
    {
        return $this->is_influencer;
    }

    /**
     * Verificar se os documentos foram verificados
     */
    public function hasVerifiedDocuments(): bool
    {
        return $this->documents_checked === 1;
    }

    /**
     * Verificar se pode fazer depósitos
     */
    public function canDeposit(): bool
    {
        return $this->cash_in_active && !$this->isBlocked();
    }

    /**
     * Verificar se pode fazer saques
     */
    public function canWithdraw(): bool
    {
        return $this->cash_out_active && !$this->isBlocked() && $this->hasVerifiedDocuments();
    }

    /**
     * Obter saldo formatado
     */
    public function getFormattedBalanceAttribute(): string
    {
        return 'R$ ' . number_format($this->balance, 2, ',', '.');
    }

    /**
     * Obter total de depósitos formatado
     */
    public function getFormattedTotalDepositAttribute(): string
    {
        return 'R$ ' . number_format($this->total_deposit, 2, ',', '.');
    }

    /**
     * Obter total de saques formatado
     */
    public function getFormattedTotalWithdrawAttribute(): string
    {
        return 'R$ ' . number_format($this->total_withdraw, 2, ',', '.');
    }

    /**
     * Obter nome para exibição (primeiro nome)
     */
    public function getFirstNameAttribute(): string
    {
        return explode(' ', $this->nomecompleto)[0];
    }

    /**
     * Obter iniciais do nome
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->nomecompleto);
        $initials = '';
        
        foreach ($names as $name) {
            if (strlen($name) > 0) {
                $initials .= strtoupper($name[0]);
                if (strlen($initials) >= 2) break;
            }
        }
        
        return $initials ?: 'U';
    }

    /**
     * Atualizar último login
     */
    public function updateLastLogin(string $ip = null): void
    {
        $this->update([
            'last_login' => now(),
            'last_ip' => $ip ?? request()->ip(),
        ]);
    }

    /**
     * Atualizar último logout
     */
    public function updateLastLogout(): void
    {
        $this->update([
            'last_logout' => now(),
        ]);
    }

    /**
     * Gerar username único
     */
    public static function generateUniqueUsername(): string
    {
        do {
            $username = 'user' . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('username', $username)->exists());
        
        return $username;
    }

    /**
     * Criar novo usuário com dados do registro
     */
    public static function createFromRegistration(array $data): self
    {
        return self::create([
            'nomecompleto' => $data['nomecompleto'],
            'email' => strtolower($data['email']),
            'username' => $data['username'] ?? self::generateUniqueUsername(),
            'telefone' => $data['telefone'],
            'password' => Hash::make($data['senha']),
            'role' => self::ROLE_USER,
            'total_deposit' => 0,
            'total_withdraw' => 0,
            'total_cashback' => 0,
            'balance' => 0,
            'document' => null,
            'bloqueado' => false,
            'documents_checked' => 0,
            'cash_in_active' => true,
            'cash_out_active' => true,
        ]);
    }

    /**
     * Scope para usuários ativos (não bloqueados)
     */
    public function scopeActive($query)
    {
        return $query->where('bloqueado', false);
    }

    /**
     * Scope para usuários com documentos verificados
     */
    public function scopeVerified($query)
    {
        return $query->where('documents_checked', 1);
    }

    /**
     * Scope para administradores
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope para usuários comuns
     */
    public function scopeUsers($query)
    {
        return $query->where('role', self::ROLE_USER);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }
}
