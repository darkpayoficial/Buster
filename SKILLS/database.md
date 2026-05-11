# Skill Banco de Dados - Buster/ALBUM-COPA

## Stack Banco de Dados
- **MySQL/MariaDB** banco de dados
- **Laravel Migrations** para schema
- **Eloquent ORM** para models
- **Database Seeders** para dados iniciais

## Estrutura
```
database/
├── migrations/        # Schema do banco
├── seeders/          # Dados iniciais
└── database.sqlite   # Banco local (desenvolvimento)
```

## Models Existentes
- User, Raspadinha, RaspadinhaPrize, Deposit, Withdrawal
- Banner, Config, GatewaysKeys, JogoHistorico

## Padrões de Migration
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabela', function (Blueprint $table) {
            $table->id();
            $table->string('campo');
            $table->boolean('active')->default(true);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabela');
    }
};
```

## Padrões de Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Example extends Model
{
    protected $fillable = [
        'campo1',
        'campo2',
    ];

    protected $casts = [
        'active' => 'boolean',
        'valor' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
```

## Regras OBRIGATÓRIAS

1. ✅ SEMPRE criar migration ANTES do model
2. ✅ USAR `$fillable` em todos os models
3. ✅ USAR `$casts` para booleanos, decimais e datas
4. ✅ DEFINIR relacionamentos nos models
5. ✅ USAR foreign keys e `onDelete('cascade')` quando necessário
6. ❌ NUNCA usar SQL raw
7. ❌ NUNCA modificar migration que já foi rodada em produção
8. ❌ NUNCA esquecer `timestamps()` nas tabelas

## Comandos Úteis
```bash
php artisan make:migration nome_da_migration
php artisan make:model NomeDoModel
php artisan migrate
php artisan migrate:rollback
php artisan db:seed
```